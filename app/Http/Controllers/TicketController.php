<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Inertia\Response;
use Inertia\Inertia;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;




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

        if (!$user->isAdmin() && !$user->isSupervisor()) {
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
        echo ($user->id);
        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,  // You can set the default role or pass it from the frontend
            'submitter_id' => $user->id,
        ]);
        echo ($user->id);


        // You can use a different response or redirect after successful registration
        return Redirect::route('tickets.fromyou.index')->with('success', 'User created.');
    }

    public function edit(Request $request, Ticket $ticket): Response
    {
        // Retrieve the JWT token from the cookie
        $token = $request->cookie('jwt_token');

        // Get the authenticated user from the token
        $user = JWTAuth::toUser($token);

        // Check access to edit the ticket

        if (!$user->canEditTicket($ticket)) {
            session()->flash('error', 'Unauthorized action.');
            return Inertia::location('/');
        }

        $ticketData = [
            'id' => $ticket->id,
            'title' => $ticket->title,
            'description' => $ticket->description,
            'created_at' => $ticket->created_at,
            'status' => $ticket->status,
        ];

        // Include additional fields if the user is an admin
        if (!$user->isEndUser()) {
            $submitter = User::findOrFail($ticket->submitter_id);
            $ticketData['submitter'] = $submitter;

            if ($ticket->assigned_tech_id) {
                $assigned_tech = User::findOrFail($ticket->assigned_tech_id);
                $ticketData['assigned_tech'] = $assigned_tech;
            } else {
                $ticketData['assigned_tech'] = null;
            }
        }

        return Inertia::render('Tickets/Edit', [
            'ticket' => $ticketData,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Retrieve the user by ID
        $ticket = Ticket::findOrFail($id);

        // Retrieve the JWT token from the cookie
        $token = $request->cookie('jwt_token');

        // Get the authenticated user from the token
        $user = JWTAuth::toUser($token);

        // Validate the input
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|min:1|max:50',
            'description' => 'nullable|min:1|max:1023',
            'assigned_tech_id' => 'nullable|exists:users,id',
            'status' => 'nullable|in:pending,in_progress,resolved',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Failed to update ticket.');
        }

        // Update the user details
        $ticket->update([
            'title' => $request->title && $user->canChange($ticket, "title")
                ? $request->title
                : $ticket->title,
            'description' => $request->description && $user->canChange($ticket, "description")
                ? $request->description
                : $ticket->description,
            'assigned_tech_id' => $request->assigned_tech_id && $user->canChange($ticket, "assigned_tech_id")
                ? $request->assigned_tech_id
                : $ticket->assigned_tech_id,
            'status' => $request->status && $user->canChange($ticket, "status")
                ? $request->status
                : $ticket->status,
        ]);

        return redirect()->route('tickets.fromyou.index')->with('success', 'ticket updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        // Find the ticket by ID
        $ticket = Ticket::findOrFail($id);

        // Retrieve the JWT token from the cookie
        $token = $request->cookie('jwt_token');

        // Get the authenticated user from the token
        $user = JWTAuth::toUser($token);

        // Check if the user can delete the ticket. Error: 403 if they cant
        $user->canDelete($ticket);

        // Delete the ticket
        $ticket->delete();

        return redirect()->route('tickets.fromyou.index')->with('success', 'Ticket deleted successfully.');
    }

    // fromyou_edit
    //
}
