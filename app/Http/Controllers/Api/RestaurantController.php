<?php

namespace App\Http\Controllers\Api;

use App\Actions\FetchRestaurantAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function fetch(Request $request, FetchRestaurantAction $action)
    {
        $req = $request->validate([
            'search' => ['nullable']
        ]);
        $action->execute($req);
        return response()->json([]);
    }
}
