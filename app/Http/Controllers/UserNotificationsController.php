<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Inertia\Response;
use Inertia\Inertia;

class UserNotificationsController extends Controller
{
    public function index(Request $request): Response
    {
        // Retrieve the JWT token from the cookie
        $token = $request->cookie('jwt_token');

        // Get the authenticated user from the token
        $user = JWTAuth::toUser($token);

        $latestNotifications = $user
                ->notifications()
                ->latest()
                ->paginate(10);

        
        $user->unreadNotifications->markAsRead();

        return Inertia::render('Notifications/Index', [
            'latest_notifications' => $latestNotifications,
        ]);
    }
    //
}
