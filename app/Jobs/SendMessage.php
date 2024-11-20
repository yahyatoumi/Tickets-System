<?php

namespace App\Jobs;

use App\Events\TicketCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendMessage implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        TicketCreatedEvent::dispatch([
            'id' => "hihihihi",
            'user_id' => "hihihihi",
            'text' => "hihihihi",
            'time' => "hihihihi",
        ]);
        //
    }
}
