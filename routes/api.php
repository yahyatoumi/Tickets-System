<?php

use App\Http\Controllers\BroadcastingController;
use Illuminate\Support\Facades\Route;


Route::post('/test', [BroadcastingController::class, 'test']);