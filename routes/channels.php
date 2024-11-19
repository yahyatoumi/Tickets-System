<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('    channel-name', function (User $user, int $orderId) {
    
    // $user->id === Order::findOrNew($orderId)->user_id;
    return true;
});