<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

// Broadcast::channel('channel_for_everyone', function (User $user, int $orderId) {
    
//     // $user->id === Order::findOrNew($orderId)->user_id;
//     return true;
// });

// Broadcast::channel('channel_for_user.{userId}', function ($user, $userId) {
//     Log::info("mimimim", $user);
//     Log::info((int) $user->id === (int) $userId);
//     return (int) $user->id === (int) $userId; // Only the user with the corresponding userId can listen
// });

// Broadcast::channel('private-channel_for_user.{userId}', function ($user, $userId) {
//     Log::info("mimimim", $user);
//     Log::info((int) $user->id === (int) $userId);
//     return (int) $user->id === (int) $userId; // Only the user with the corresponding userId can listen
// });


// Broadcast::routes([
//     'middleware' => ['web', \App\Http\Middleware\JWTMiddleware::class]
// ]);