<?php

use App\Models\Juz;
use App\Models\Page;
use App\Models\Sajda;
use App\Models\Surah;
use App\Models\Verse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});


Route::get('suras', function () {
    $surahsJson = collect(json_decode(Storage::get("public/metadata/surah.json")))->map(function ($sura) {
        $suraModel = Surah::create([
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

        $verses = json_decode(Storage::get("public/suras/surah_" . ((int) $sura->index) . ".json"))->verse;

        $suraModel->verses()->saveMany(collect($verses)->map(function ($verse) {
            return (new Verse([
                'text' => $verse
            ]));
        }));

        $suraModel->juzs()->saveMany(collect($sura->juz)->map(function ($juz) {
            return (new Juz([
                'start_verse' => (int)str_replace("verse_", "", $juz->verse->start),
                'end_verse' => (int)str_replace("verse_", "", $juz->verse->end),
            ]));
        }));
    });

    // pages
    $pagesJson = collect(json_decode(Storage::get("public/metadata/page.json")))->map(function ($page) {
        Page::create([
            'start_surah_id' => (int) $page->start->index,
            'start_verse_id' => (int) str_replace("verse_", "", $page->start->verse),
            'end_surah_id' => (int) $page->end->index,
            'end_verse_id' => (int) str_replace("verse_", "", $page->end->verse),
        ]);
    });

    // sajdas
    $sajdasJson = collect(json_decode(Storage::get("public/metadata/sajda.json")))->map(function ($sajda) {
        Sajda::create([
            'surah_id' => (int) $sajda->suraIndex,
            'verse_id' => (int) str_replace("verse_", "", $sajda->verse),
            'obligatory' => $sajda->obligatory
        ]);
    });

});
