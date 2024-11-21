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
        Inertia::share('auth', fn() => [
            'user' => fn() => optional(JWTAuth::user())->only('id', 'username', 'email', 'created_at', 'role'),
        ]);
        
        Inertia::share('notifications', function () {
            $user = JWTAuth::user();
            if (!$user) {
                return [];
            }
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
