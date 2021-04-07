<?php

namespace App\Notifications\Discord;

use App\Models\Page;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;

class RandomPageNotification extends Notification
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

    public function via($notifiable)
    {
        return [DiscordChannel::class];
    }

    public function toDiscord($notifiable)
    {
        $page = Page::all()->random();
        return DiscordMessage::create("من الايه {$page->start_verse_id} إلي الاية {$page->end_verse_id} ", [
            'image' => [
                'url' => $page->image
            ]
        ]);
    }
}
