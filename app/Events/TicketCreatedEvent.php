<?php

namespace App\Events;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TicketCreatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Ticket $ticket;
    public User $user;

    /**
     * Create a new event instance.
     */
    public function __construct(Ticket $ticket, User $user)
    {
        $this->ticket = $ticket;
        $this->user = $user;
        Log::info('TicketCreatedEvent dispatched', ['ticket' => $ticket, 'user' => $user]);
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel_for_everyone.'. $this->user->id),
            // new PrivateChannel('channel_for_everyone'),
            new Channel('channel_for_everyone.'. $this->user->id),
        ];
    }

    public function broadcastWith()
    {
        return [
            'ticket' => $this->ticket,
            'user' => $this->user,
        ];
    }

    public function broadcastAs()
    {
        return 'ticket.created';
    }
}
