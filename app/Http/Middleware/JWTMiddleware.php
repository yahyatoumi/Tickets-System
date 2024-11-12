<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Exception;
use Illuminate\Support\Facades\Cookie;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->getPathInfo(); // Gets the request path (e.g., '/login')

        try {
            // Retrieve token from cookie
            $token = $request->cookie('jwt_token');

            if ($token) {
                $user = JWTAuth::setToken($token)->authenticate();

                // If authenticated and accessing /login, redirect to homepage
                if ($path === '/login') {
                    return redirect('/');
                }
            } else {
                // If no token, forget cookie and redirect to login if not already there
                if ($path !== '/login') {
                    $cookie = Cookie::forget('jwt_token');
                    return redirect('/login')->withCookie($cookie);
                }
            }
        } catch (Exception $e) {
            // On exception (e.g., token invalid), forget cookie and redirect to login
            if ($path !== '/login') {
                $cookie = Cookie::forget('jwt_token');
                return redirect('/login')->withCookie($cookie);
            }
        }

        return $next($request); // Proceed with the request if authenticated or on /login
    }
}
