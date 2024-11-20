<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Broadcast::routes();
        // Broadcast::routes(['middleware' => ['auth:api']]);

        // Define a private channel, for example, for a user with a specific ID
        Broadcast::channel('channel_for_user.{userId}', function ($user, $userId) {
            // Check if the authenticated user is authorized to listen to this channel
            return (int) $user->id === (int) $userId;
        });
        //
    }
}
