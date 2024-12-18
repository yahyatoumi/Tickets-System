<?php

namespace App\Events;

use App\Models\Comment;
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

class CommentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Comment $comment;
    public User $user;
    public Ticket $ticket;
    public User $whoCommented;


    /**
     * Create a new event instance.
     */
    public function __construct(Comment $comment, User $user, Ticket $ticket, User $whoCommented)
    {
        $this->comment = $comment;
        $this->user = $user;
        $this->ticket = $ticket;
        $this->whoCommented = $whoCommented;
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
            'comment' => $this->comment,
            // 'user' => $this->user,
            'ticket' => $this->ticket,
            'whoCommented' => $this->whoCommented,
        ];
    }

    public function broadcastAs()
    {
        return 'new.comment';
    }
}
