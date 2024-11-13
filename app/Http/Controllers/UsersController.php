<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;


class UsersController extends Controller
{
    public function index(): Response
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    public function create(Request $request)
    {
        // Get request
        if ($request->isMethod('get')) {
            return Inertia::render('Users/Create', [
                'test' => 'test signup',
            ]);
        }

        // Post request
        // Validate the input
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users|max:50',
            'email' => 'nullable|email|unique:users,email', // 'nullable' makes it optional
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,supervisor,end_user',  // Validate role to be either 'admin' or 'supervisor' or 'end_user'
        ]);

        if ($validator->fails()) {
            return redirect('users/create')
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Failed to create user.'); // Keeps the user's input data
        }

        // Create the user
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,  // You can set the default role or pass it from the frontend
        ]);

        // You can use a different response or redirect after successful registration
        return Redirect::route('users.create')->with('success', 'User created.');
    }

    public function edit(User $user): Response
    {
        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        // Retrieve the user by ID
        $user = User::findOrFail($id);

        // Validate the input
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:50|unique:users,username,' . $id, // Ignore the current user's username for uniqueness
            'email' => 'nullable|email|unique:users,email,' . $id, // Ignore current user's email for uniqueness
            'role' => 'required|in:admin,supervisor,end_user', // Ensure role is valid
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Failed to update user.');
        }

        // Update the user details
        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Delete the user
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function search(Request $request, Ticket $ticket)
    {
        // Get search query from request
        $query = $request->input('query');

        // Fetch users matching the search criteria
        $users = User::query()
            ->where('username', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->paginate(6);

        // Return matching users as a response
        return response()->json([
            'users' => $users,
        ]);
        // return Inertia::render("Tickets/FromYou/{ticket}/edit", [
        //     'users' => $users,
        // ]);
    }
    //
}
