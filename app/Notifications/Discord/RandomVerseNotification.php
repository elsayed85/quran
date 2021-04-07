<?php

namespace App\Notifications\Discord;

use App\Models\Verse;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;

class RandomVerseNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [DiscordChannel::class];
    }

    public function toDiscord($notifiable)
    {
        $verses = implode("", Verse::skip(rand(1, 6233))->take(3)->get()->map(fn ($verse) =>  "> ** {$verse->text} ** \n")->toArray());
        return DiscordMessage::create("****بسم الله الرحمن الرحيم**** ## \n {$verses}");
    }
}
