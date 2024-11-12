<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class TestController extends Controller
{
    public function show()
    {
        return Inertia::render('Test', [
            'test' => 'testttt',
        ]);
    }
}
