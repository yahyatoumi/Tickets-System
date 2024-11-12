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
            return [
                'user' => JWTAuth::user()
                    ? JWTAuth::user()->only('id', 'username', 'email', 'created_at', "role") // adjust fields as needed
                    : null,
            ];
        });
        //
    }
}
