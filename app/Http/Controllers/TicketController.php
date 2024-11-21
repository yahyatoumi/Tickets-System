<?php

namespace App\Http\Controllers;

use App\Events\TestEvent;
use App\Events\TicketCreatedEvent;
use App\Events\TicketUpdateEvent;
use App\Http\Controllers\Controller;
use App\Jobs\SendMessage;
use App\Models\Notification;
use App\Models\Ticket;
use App\Models\UploadedFile;
use App\Models\User;
use App\Notifications\NewNotification;
use Inertia\Response;
use Inertia\Inertia;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    public function from_you(Request $request): Response
    {
        // Log::info("from_you");

        // Log::info('Request details:', [
        //     'headers' => $request->headers->all(),
        //     'query_params' => $request->query->all(),
        //     'body_params' => $request->all(),
        //     'cookies' => $request->cookies->all(),
        //     'ip' => $request->ip(),
        //     'url' => $request->fullUrl(),
        //     'method' => $request->method(),
        // ]);
        // Retrieve the JWT token from the cookie
        $token = $request->cookie('jwt_token');

        // Get the authenticated user from the token
        $user = JWTAuth::toUser($token);



        // Fetch tickets where submitter_id matches the authenticated user's ID
        $tickets = Ticket::where('submitter_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->select('id', 'title', 'status', 'description', 'created_at', 'assigned_tech_id')
            ->paginate(10);

        if (!$user->canSee("assigned_tech_id")) {
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
            'files' => 'nullable|array', // Ensure files are an array
            'files.*' => 'file|mimes:jpg,jpeg,png,pdf,php,html|max:10000', // Allow specific file types 10000 kilobyes | 10 MB max for each file
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

        // broadcast(new TicketCreatedEvent($ticket));

        $ticket->load('submitter');

        $ticket->broadcastCreationToAdminsAndOwner(TicketCreatedEvent::class);

        // event(new TicketCreatedEvent($ticket, $user));


        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $ticketId = $ticket->id;

                // Generate a random name for the file
                $randomName = Str::random(10) . '.' . $file->getClientOriginalExtension(); // Random 10 characters + original extension

                // Define the path where the file will be stored
                $filePath = $file->storeAs("uploads/tickets/{$ticketId}", $randomName, 'public');

                // Create the UploadedFile model instance and store the file details
                $uploadedFile = new UploadedFile();
                $uploadedFile->filename = $randomName; // Store the random file name
                $uploadedFile->original_name = $file->getClientOriginalName(); // Store the original file name
                $uploadedFile->file_path = $filePath; // Store the file path
                $uploadedFile->ticket_id = $ticketId; // Associate file with ticket
                $uploadedFile->save();
            }
        }

        // You can use a different response or redirect after successful registration
        return Redirect::route('tickets.fromyou.index')->with('success', 'Ticket created.');
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
            "files" => $ticket->uploadedFiles,
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

        $updates = [];
        $updatedFields = [];

        if ($request->title && $ticket->title !== $request->title && $user->canChange($ticket, "title")) {
            $updates['title'] = $request->title;
            $updatedFields[] = 'title';
        }

        if ($request->description && $ticket->description !== $request->description && $user->canChange($ticket, "description")) {
            $updates['description'] = $request->description;
            $updatedFields[] = 'description';
        }

        if ($request->assigned_tech_id && $ticket->assigned_tech_id !== $request->assigned_tech_id && $user->canChange($ticket, "assigned_tech_id")) {
            $updates['assigned_tech_id'] = $request->assigned_tech_id;
            $updatedFields[] = 'assigned_tech_id';
        }

        if ($request->status && $ticket->status !== $request->status && $user->canChange($ticket, "status")) {
            $updates['status'] = $request->status;
            $updatedFields[] = 'status';
        }

        if (!empty($updates)) {
            $ticket->update($updates);
        }

        $ticket->broadcastUpdateToAdminsAndOwner($user, $updatedFields);

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
