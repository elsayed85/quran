<?php

use App\Bio\BlastAPI;
use App\Models\Juz;
use App\Models\Manzil;
use App\Models\Page;
use App\Models\Rub;
use App\Models\Ruku;
use App\Models\Sajda;
use App\Models\Surah;
use App\Models\User;
use App\Models\Verse;
use App\Notifications\Discord\RandomPageNotification;
use App\Notifications\Discord\RandomVerseNotification;
use App\Notifications\Discord\WelcomeNotification;
use Illuminate\Contracts\Pipeline\Hub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Laravel\Socialite\Facades\Socialite;
use NotificationChannels\Discord\Discord;

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', function () {
    Artisan::call("key:generate");
});

Route::get('login/discord', function () {
    return Socialite::driver('discord')->redirect();
})->middleware(['guest']);

Route::get('handel/discord', function (Request $request) {
    try {
        $userData = Socialite::driver('discord')->user();
        $channelId = app(Discord::class)->getPrivateChannel($userData->id);
        $password = null;

        if ($user = User::whereEmail($userData->email)->first()) {
            $user->update([
                'discord_user_id' => $userData->id,
                'discord_private_channel_id' => $channelId,
                'name' => $userData->name,
                'avatar' => $userData->user['avatar'],
                'email_verified_at' => $userData->user['verified'] ?  now() : null
            ]);
        } else {
            $password = str_random(8);
            $user = User::create([
                'discord_user_id' => $userData->id,
                'discord_private_channel_id' => $channelId,
                'email' => $userData->email,
                'name' => $userData->name,
                'avatar' => $userData->user['avatar'],
                'password' => Hash::make($password),
                'email_verified_at' => $userData->user['verified'] ?  now() : null
            ]);
            $user->notify(new WelcomeNotification($password));
        }

        auth()->login($user);
        return redirect(route('dashboard'));
    } catch (\Throwable $th) {
        dd($th->getMessage());
    }
})->middleware(['guest']);

Route::get('send', function () {
    auth()->user()->notify(new RandomVerseNotification());
    auth()->user()->notify(new RandomPageNotification());
    return redirect(route('dashboard'))->withMessage('done!');
})->middleware(['auth'])->name('send');




Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Route::get('white-background', function () {
//     $files = glob(public_path("storage\quran_images\*.png"));
//     foreach ($files as $file) {
//         $image = Image::canvas(1024, 1656, '#fff')->insert($file)->save($file);
//     }
// });


Route::get('seed', function () {

    // surahs
    $surahsModels = collect(json_decode(Storage::get("public/metadata/surah.json")))->map(function ($sura) {
        return Surah::create([
            'name' => $sura->title,
            'name_en' => $sura->titleEn,
            'name_ar' => $sura->titleAr,
            'pages' => $sura->pages,
            'page' => $sura->page,
            'place' => $sura->place,
            'start' => $sura->start,
            'type' => $sura->type,
            'revelationOrder' => $sura->revelationOrder,
            'rukus' => $sura->rukus,
            'count' => $sura->count
        ]);
    });

    // verses
    $surahsModels->map(function ($sura) {
        $verses = json_decode(Storage::get("public/suras/surah_{$sura->id}.json"))->verse;
        $sura->verses()->saveMany(collect($verses)->map(function ($verse) {
            return (new Verse([
                'text' => $verse
            ]));
        }));
    });

    // pages
    collect(json_decode(Storage::get("public/metadata/page.json")))->map(function ($page) {
        Page::create([
            'start_surah_id' => (int) $page->start->index,
            'start_verse_id' => (int) str_replace("verse_", "", $page->start->verse),
            'end_surah_id' => (int) $page->end->index,
            'end_verse_id' => (int) str_replace("verse_", "", $page->end->verse),
        ]);
    });

    // juzs
    collect(json_decode(Storage::get("public/metadata/juz.json")))->map(function ($juz) {
        Juz::create([
            'start_surah_id' => (int) $juz->start->index,
            'start_verse_id' => (int) str_replace("verse_", "", $juz->start->verse),
            'end_surah_id' => (int) $juz->end->index,
            'end_verse_id' => (int) str_replace("verse_", "", $juz->end->verse),
        ]);
    });

    // manzils
    collect(json_decode(Storage::get("public/metadata/manzil.json")))->map(function ($manzil) {
        Manzil::create([
            'start_surah_id' => (int) $manzil->start->index,
            'start_verse_id' => (int) str_replace("verse_", "", $manzil->start->verse),
            'end_surah_id' => (int) $manzil->end->index,
            'end_verse_id' => (int) str_replace("verse_", "", $manzil->end->verse),
        ]);
    });

    // Rubs
    collect(json_decode(Storage::get("public/metadata/rub.json")))->map(function ($rub) {
        Rub::create([
            'start_surah_id' => (int) $rub->start->index,
            'start_verse_id' => (int) str_replace("verse_", "", $rub->start->verse),
            'end_surah_id' => (int) $rub->end->index,
            'end_verse_id' => (int) str_replace("verse_", "", $rub->end->verse),
        ]);
    });

    // Rukus
    collect(json_decode(Storage::get("public/metadata/ruku.json")))->map(function ($ruku) {
        Ruku::create([
            'surah_id' => (int) $ruku->suraIndex,
            'manzil_id' => (int) $ruku->manzil,
            'start_verse_id' => (int) str_replace("verse_", "", $ruku->start),
            'end_verse_id' => (int) str_replace("verse_", "", $ruku->end),
        ]);
    });

    // sajdas
    collect(json_decode(Storage::get("public/metadata/sajda.json")))->map(function ($sajda) {
        Sajda::create([
            'surah_id' => (int) $sajda->suraIndex,
            'verse_id' => (int) str_replace("verse_", "", $sajda->verse),
            'obligatory' => $sajda->obligatory
        ]);
    });
});
