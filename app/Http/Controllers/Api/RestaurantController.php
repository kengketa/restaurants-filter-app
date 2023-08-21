<?php

namespace App\Http\Controllers\Api;

use App\Actions\FetchRestaurantAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\FetchRestaurantRequest;
use http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RestaurantController extends Controller
{
    public function fetch(FetchRestaurantRequest $request, FetchRestaurantAction $action): JsonResponse
    {
        $cacheKey = 'search-cache-' . trim($request['search']);
        $restaurantData = Cache::remember($cacheKey, now()->addHours(24), function () use ($request, $action) {
            $restaurants = $action->execute($request->validated());
            return $restaurants;
        });
        return response()->json($restaurantData);
    }
}
