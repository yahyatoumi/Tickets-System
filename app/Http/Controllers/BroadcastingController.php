<?php
// app/Http/Controllers/BroadcastingController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Pusher\Pusher;

class BroadcastingController extends Controller
{
    public function test(Request $request)
    {
        $user = JWTAuth::user();
        Log::info("usrrrrrr testtttt");
        Log::info($user);
        
        return response()->json(['auth' => true, 'user' => $user], 201);
    }

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

        // The channel name from the request
        $channelName = $request->input('channel_name');
        $id = substr(strrchr($channelName, '.'), 1);
        Log::info($channelName);
        Log::info($id);

        if (!$user || (int)$id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $appKey = env('REVERB_APP_KEY');
        $appSecret = env('REVERB_APP_SECRET');
        $appId = env('REVERB_APP_ID');
    
        $pusher = new Pusher(
            $appKey,
            $appSecret,
            $appId,
            [
                'cluster' => 'eu',
                'useTLS' => env('REVERB_SCHEME', 'https') === 'https'
            ]
        );
    
        

    
        // Generate the socket authentication signature
        $auth = $pusher->authorizeChannel($channelName, $request->input('socket_id'));
    
        return response()->json(json_decode($auth)) ;
        
        return response()->json(['auth' => true, 'user' => $user], 200);
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
