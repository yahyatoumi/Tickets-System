<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserNotificationsController;
use App\Http\Controllers\BroadcastingController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\JWTMiddleware;
use App\Http\Middleware\SupervisorAndAdminMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\TicketEditAccessMidlleware;
use Illuminate\Support\Facades\Broadcast;

// Route::post('/mybroadcasting/auth', [BroadcastingController::class, 'authorize']);
// Route::post('/broadcasting/auth', [BroadcastingController::class, 'authorize']);

Route::middleware([JWTMiddleware::class])->group(function () {

	// Admins only
	Route::middleware([AdminMiddleware::class])->group(function () {
		Route::get('/users/create', [UsersController::class, 'create'])->name('users.create.index');
		Route::post('/users/create', [UsersController::class, 'create'])->name('users.create');
		Route::get('users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
		Route::put('users/{user}', [UsersController::class, 'update'])->name('users.update');
		Route::delete('users/{user}', [UsersController::class, 'destroy'])->name('users.delete');

		Route::post('/register', [UserController::class, 'register']);

		// Search for user
		Route::get('users/search', [UsersController::class, 'search'])->name('users.search');
	});

	// Supervisors and Admins
	Route::middleware([SupervisorAndAdminMiddleware::class])->group(function () {
		Route::get('/users', [UsersController::class, 'index'])->name('users.index');

		Route::get('/tickets/toyou', [TicketController::class, 'to_you'])->name('tickets.toyou.index');
		Route::get('/tickets/toyou/edit', [TicketController::class, 'edit'])->name('tickets.toyou.edit.index');
		Route::post('/tickets/toyou/edit', [TicketController::class, 'edit'])->name('tickets.toyou.edit');
	});

	// All users
	Route::get(
		'/',
		function () {
			return Inertia::render(
				'Home',
				[
					'title' => 'Homepage',
				]
			);
		}
	)->name('homepage');
	Route::get('/test', [TestController::class, 'show'])->name('test.show');
	Route::delete('/logout', [UserController::class, 'logout']);


	Route::get('/tickets/fromyou', [TicketController::class, 'from_you'])->name('tickets.fromyou.index');
	Route::get('/tickets/fromyou/create', [TicketController::class, 'create'])->name('tickets.fromyou.create.index');
	Route::post('/tickets/fromyou/create', [TicketController::class, 'create'])->name('tickets.fromyou.create');

	Route::middleware([TicketEditAccessMidlleware::class])->group(function () {
		Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('ticket.edit');
	});

	Route::put('/ticket/{ticket}', [TicketController::class, 'update'])->name('ticket.update');
	Route::delete('/ticket/{ticket}', [TicketController::class, 'destroy'])->name('ticket.delete');

	Route::get('/notifications', [UserNotificationsController::class, 'index'])->name('notifications.index');

	// Route::post('/broadcasting/auth', [BroadcastController::class, 'authenticate']);
	// Broadcast::routes();




	// No need for auth
	Route::get('/login', [UserController::class, 'login'])->name('user.login');
	Route::post('/login', [UserController::class, 'login']);


});


Route::get('/about', function () {
	return Inertia::render('About'); // The 'About' component should match a Vue component file in your setup
});

Route::get(
	'/contact',
	function () {
		return Inertia::render(
			'Contact',
			[
				'title' => 'Contact',
			]
		);
	}
)->name('contact');
