<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inertia::share('auth', function () {
            $user = JWTAuth::user();

            return [
                'user' => $user
                    ? $user->only('id', 'username', 'email', 'created_at', "role") // adjust fields as needed
                    : null,
            ];
        });

        // Share notifications
        Inertia::share('notifications', function () {
            $user = JWTAuth::user();
            if (!$user) {
                return [];
            }
            $latestNotifications = $user
                ->notifications()
                ->latest()
                ->take(10)
                ->get();

            $unreadCount = $user->unreadNotifications()
                ->count();

            $data = [
                'unread_count' => $unreadCount,
            ];

            return $data;
        });
        //
    }
}
