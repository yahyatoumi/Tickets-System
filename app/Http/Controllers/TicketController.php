<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Inertia\Response;
use Inertia\Inertia;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;



class TicketController extends Controller
{
    public function from_you(Request $request): Response
    {
        // Retrieve the JWT token from the cookie
        $token = $request->cookie('jwt_token');

        // Get the authenticated user from the token
        $user = JWTAuth::toUser($token);

        // Fetch tickets where submitter_id matches the authenticated user's ID
        $tickets = Ticket::where('submitter_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->select('id', 'title', 'status', 'description', 'created_at', 'assigned_tech_id')
            ->paginate(10);

        if (!$user->isAdmin() && !$user->isSupervisor() ){
            $tickets->makeHidden(['assigned_tech_id']);
        }

        return Inertia::render('Tickets/FromYou/Index', [
            'tickets' => $tickets,
        ]);
    }

    public function to_you(Request $request): Response
    {
        // Retrieve the JWT token from the cookie
        $token = $request->cookie('jwt_token');

        // Get the authenticated user from the token
        $user = JWTAuth::toUser($token);

        // Fetch tickets where assigned_tech_id matches the authenticated user's ID
        $tickets = Ticket::where('assigned_tech_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->select('id', 'title', 'status', 'description', 'created_at', 'submitter_id')
            ->paginate(10);

        return Inertia::render('Tickets/ToYou/Index', [
            'tickets' => $tickets,
        ]);
    }

    public function create(Request $request)
    {
        // Retrieve the JWT token from the cookie
        $token = $request->cookie('jwt_token');

        // Get the authenticated user from the token
        $user = JWTAuth::toUser($token);

        
        // Get request
        if ($request->isMethod('get')) {
            return Inertia::render('Tickets/FromYou/Create', [
                'test' => 'test signup',
            ]);
        }

        // Post request
        // Validate the input
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:50',
            'description' => 'required|max:1023',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Failed to create ticket.'); // Keeps the user's input data
        }

        // Create the user
        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,  // You can set the default role or pass it from the frontend
            'submitter_id' => $user->id,
        ]);

        // You can use a different response or redirect after successful registration
        return Redirect::route('tickets.fromyou.index')->with('success', 'User created.');
    }

    public function fromyou_edit(Request $request, Ticket $ticket): Response
    {
        // Retrieve the JWT token from the cookie
        $token = $request->cookie('jwt_token');

        // Get the authenticated user from the token
        $user = JWTAuth::toUser($token);
    
        $ticketData = [
            'id' => $ticket->id,
            'title' => $ticket->title,
            'description' => $ticket->description,
            'created_at' => $ticket->created_at,
        ];
    
        // Include additional fields if the user is an admin
        if ($user->role === 'admin') {
            $ticketData['assigned_tech_id'] = $ticket->assigned_tech_id;
            $ticketData['submitter_id'] = $ticket->submitter_id;
        }


        return Inertia::render('Tickets/FromYou/Edit', [
            'ticket' => $ticketData,
        ]);
    }

    // fromyou_edit
    //
}
