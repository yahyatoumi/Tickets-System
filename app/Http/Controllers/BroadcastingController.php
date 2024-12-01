<?php
// app/Http/Controllers/BroadcastingController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class BroadcastingController extends Controller
{
    /**
     * Authorize the user for private channels.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authorize(Request $request)
    {

        $user = JWTAuth::user();
        Log::info("usrrrrrr");
        Log::info($user);
        
        return response()->json(['auth' => true], 201);
    }

    /**
     * Custom logic to check if the user can access the channel.
     *
     * @param  string  $channel
     * @return bool
     */
    private function canAccessChannel($channel)
    {
        // Example: Check if the user can access the specified channel
        return in_array($channel, $this->getUserChannels());
    }

    /**
     * Example list of channels the user can access.
     *
     * @return array
     */
    private function getUserChannels()
    {
        // This could be based on roles, user subscriptions, etc.
        return [
            'private-channel-1',
            'private-channel-2',
        ];
    }
}
