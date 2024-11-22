<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Inertia\Inertia;
use Inertia\Response;

class CommentController extends Controller
{
    // public function index(Request $request, $ticketId): Response
    // {
    //     return Inertia::render('Users/Index', [
    //         'comments' => fn() => Comment::where('ticket_id', $ticketId)
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(10),
    //     ]);
    // }

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
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Failed to create user.'); // Keeps the user's input data
        }

        $user = JWTAuth::user();

        $comment = new Comment();
        $comment->body = $request->body; // Set the body from the request
        $comment->ticket_id = $ticket->id; // Assign ticket_id manually
        $comment->user_id = $user->id; // Assign user_id manually
        $comment->save();

        $comment->broadcastCommentToAdminsAndOwner($user);

        return back()->with([
            'newComment' => $comment,
        ]);
    }
    //
}
