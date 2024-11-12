<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;

use Exception;


class UserController extends Controller
{


    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            // Validate the input
            $validator = Validator::make($request->all(), [
                'username' => 'required|max:50',
            ]);

            if ($validator->fails()) {
                return back()
                ->withErrors($validator)
                ->withInput();
                return redirect('')
                ->withErrors($validator)
                ->withInput();
            }

            // Check credentials
            $credentials = $request->only('username', 'password');
            if (!$token = JWTAuth::attempt($credentials)) {
                return back()
                ->withErrors(['username' => 'Invalid username or password.'])
                ->withInput();
            }

            // Create a cookie to store the JWT token
            $cookie = cookie('jwt_token', $token, 60 * 24, '/', null, false, true);

            // Return the token in the cookie or redirect to the home page
            return redirect('/')->withCookie($cookie);
        } else if ($request->isMethod('get')) {
            return Inertia::render('Auth/Login', [
                'test' => 'test login',
            ]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): RedirectResponse
    {
        try {
            // Retrieve token from the cookie
            $token = $request->cookie('jwt_token');

            if ($token) {
                // Invalidate the token to make it unusable
                JWTAuth::setToken($token)->invalidate();
            }

            // Forget the cookie
            $cookie = Cookie::forget('jwt_token');

            // Redirect to the login page or return a success response with the cookie cleared
            return redirect('/login')->withCookie($cookie);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to log out'], 500);
        }
    }
    //
}
