<?php

namespace App\Listeners;

use App\Events\TicketUpdateEvent;
use App\Notifications\NewNotification;
use App\Notifications\TicketUpdateNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendTicketUpdatedNotification
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
    public function handle(TicketUpdateEvent $event): void
    {
        $ticket = $event->ticket;
        $ticket->load('submitter');

        $user = $event->user;
        $updater = $event->updater;
        $updatedFields = $event->updatedFields;

        Log::info("brodcasted from listener");
        $user->notify(new TicketUpdateNotification($ticket, $user, $updater, $updatedFields));
    }
}
