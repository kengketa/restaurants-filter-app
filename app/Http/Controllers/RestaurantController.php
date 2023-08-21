<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Restaurant/Index', []);
    }
}
