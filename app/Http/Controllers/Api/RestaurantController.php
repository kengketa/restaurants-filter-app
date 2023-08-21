<?php

namespace App\Http\Controllers\Api;

use App\Actions\FetchRestaurantAction;
use App\Http\Controllers\Controller;
use http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function fetch(Request $request, FetchRestaurantAction $action): JsonResponse
    {
        $req = $request->validate([
            'search' => ['nullable']
        ]);
        $restaurants = $action->execute($req);
        return response()->json($restaurants);
    }
}
