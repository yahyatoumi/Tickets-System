<?php

namespace App\Listeners;

use App\Events\TicketCreatedEvent;
use App\Models\User;
use App\Notifications\NewNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendTicketCreatedNotification implements ShouldQueue
{
    use InteractsWithQueue;
    
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
    public function handle(TicketCreatedEvent $event): void
    {
        $ticket = $event->ticket;
        $ticket->load('submitter');

        $user = $event->user;

        Log::info("brodcasted from listener");
        $user->notify(new NewNotification($ticket, $user));
    }
}
