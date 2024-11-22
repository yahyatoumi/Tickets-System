<?php

namespace App\Listeners;

use App\Events\CommentEvent;
use App\Notifications\NewCommentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendCommentCreatedNotification
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
    public function handle(CommentEvent $event): void
    {
        $comment = $event->comment;

        $user = $event->user;

        Log::info("brodcasted from listener");
        $user->notify(new NewCommentNotification($comment, $user));
        //
    }
}
