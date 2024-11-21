<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class CommentController extends Controller
{
    public function create(Request $request, Ticket $ticket)
    {
        // Post request
        // Validate the input
        $validator = Validator::make($request->all(), [
            'body' => 'required|max:1023',
            'files' => 'nullable|array', // Ensure files are an array
            'files.*' => 'file|mimes:jpg,jpeg,png,pdf,php,html|max:10000', // Allow specific file types 10000 kilobyes | 10 MB max for each file
        ]);

        if ($validator->fails()) {
            return redirect('')
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Failed to create user.'); // Keeps the user's input data
        }

        $input = $request->all();

        // Retrieve the JWT token from the cookie
        // $token = $request->cookie('jwt_token');

        // // Get the authenticated user from the token
        // $user = JWTAuth::toUser($token);

        $user = JWTAuth::user();

        $input['user_id'] = $user->id;

        $user = Comment::create($input);

        return back();

        // You can use a different response or redirect after successful registration
        // return Redirect::route('notifications.index')->with('success', 'User created.');
    }
    //
}
