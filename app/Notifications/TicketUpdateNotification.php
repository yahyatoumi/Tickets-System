<?php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use InvalidArgumentException;

class TicketUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Ticket $ticket;
    public User $user;
    public User $updater;
    public array $updatedFields;


    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket, User $user, User $updater, array $updatedFields = [])
    {
        $this->ticket = $ticket;
        $this->user = $user;
        $this->updater = $updater;
        $this->updatedFields = $updatedFields;
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
            'updater' => $this->updater,
            'ticket' => $this->ticket,
            'type' => "updated",
            'updated_fields' => $this->updatedFields,
        ];
    }
}
