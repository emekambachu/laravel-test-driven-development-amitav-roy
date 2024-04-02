<?php

namespace App\Listeners;

use App\Events\VideoPublishedEvent;
use App\Mail\VideoPublishedEmailToOwner;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class VideoPublishedEmailOwnerListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(VideoPublishedEvent $event)
    {
        $user = User::find($event->video->user_id);
        Mail::to($user)->queue(new VideoPublishedEmailToOwner($event->video));
    }
}
