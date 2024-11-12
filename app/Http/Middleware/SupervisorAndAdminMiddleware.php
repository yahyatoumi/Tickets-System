<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;


class SupervisorAndAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and has an 'admin' role
        if (JWTAuth::check()) {
            $userRole = JWTAuth::user()->role;
            if ($userRole === 'admin' || $userRole === 'supervisor'){
                return $next($request);  // Allow access
            }
        }

        // Optionally, redirect or return an error if the user is not an admin
        return redirect('/')->with('error', 'Unauthorized access.');
    }
}
