<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NewCommentNotification extends Notification
{
    use Queueable;

    public Comment $comment;
    public User $user;
    public User $whoCommented;
    public Ticket $ticket;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment, User $user, Ticket $ticket, User $whoCommented)
    {
        
        $this->comment = $comment;
        $this->user = $user;
        $this->ticket = $ticket;
        $this->whoCommented = $whoCommented;
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user' => $this->user,
            'comment' => $this->comment,
            'ticket' => $this->ticket,
            'whoCommented' => $this->whoCommented,
            'type' => "comment",
            //
        ];
    }
}
