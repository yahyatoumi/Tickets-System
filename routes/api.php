<?php

use App\Http\Controllers\BroadcastingController;
use Illuminate\Support\Facades\Route;


Route::post('/mybroadcasting/auth', [BroadcastingController::class, 'authorize']);