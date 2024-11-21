<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class CommentInTicketMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Retrieve the JWT token from the cookie
        $token = $request->cookie('jwt_token');
        
        // Get the authenticated user from the token
        $user = JWTAuth::toUser($token);
        
        // Retrieve the ticket from the route
        $ticket = $request->route('ticket');

        // Check access
        if (!$user->canComment($ticket)) {
            // Set flash error message for Inertia and redirect
            session()->flash('error', 'Unauthorized action.');
            return redirect('/');
        }

        // Allow access if authorized
        return $next($request);
    }
}
