<?php

namespace App\Actions;

use App\Transformers\GoogleMapTransformer;
use GuzzleHttp\Client;

class FetchRestaurantAction
{
    public function execute(array|null $filters): array
    {
        $transformedData = [];
        $rawData = $this->getRestaurantsBySearch($filters['search'], $filters['nextPageToken'] ?? null);
        if ($rawData == null) {
            $transformedData['data'] = [];
            $transformedData['meta'] = [];
            return $transformedData;
        }
        $transformedData['data'] = fractal($rawData->results, new GoogleMapTransformer())->toArray()['data'] ?? [];
        $transformedData['meta']['next_page_token'] = isset($rawData?->next_page_token) ? $rawData->next_page_token : null;
        return $transformedData;
    }

    private function getRestaurantsBySearch(string|null $keyword, string|null $nextPageToken)
    {
        if ($keyword == null) {
            return null;
        }
        $client = new Client();
        $response = $client->get("https://maps.googleapis.com/maps/api/place/textsearch/json", [
            'query' => [
                'query' => 'restaurant in ' . $keyword,
                'key' => config('services.google.maps_api_key'),
                'pagetoken' => $nextPageToken
            ],
        ]);
        $rawData = json_decode($response->getBody());
        if ($rawData->status === 'INVALID_REQUEST') {
            return null;
        }
        return $rawData;
    }

    private function getLocationByPlace(string|null $search): null|string
    {
        if ($search == null) {
            return null;
        }
        $client = new Client();
        $geocodingUrl = 'https://maps.googleapis.com/maps/api/geocode/json';
        $geocodingParams = [
            'query' => [
                'address' => $search,
                'key' => config('services.google.maps_api_key'),
            ],
        ];
        $geocodingResponse = $client->get($geocodingUrl, $geocodingParams);
        $geocodingData = json_decode($geocodingResponse->getBody(), true);
        if ($geocodingData['status'] !== 'OK') {
            return null;
        }
        $location = $geocodingData['results'][0]['geometry']['location'];
        $latitude = $location['lat'];
        $longitude = $location['lng'];
        return "$latitude,$longitude";
    }

    private function getRestaurantsByLocation(string $location, string|null $nextPageToken): object|null
    {
        $client = new Client();
        $response = $client->get("https://maps.googleapis.com/maps/api/place/nearbysearch/json", [
            'query' => [
                'location' => $location,
                'radius' => 50000, // in meters
                'types' => 'restaurant',
                'key' => config('services.google.maps_api_key'),
                'pagetoken' => $nextPageToken
            ],
        ]);
        $rawData = json_decode($response->getBody());
        if ($rawData->status === 'INVALID_REQUEST') {
            return null;
        }
        return $rawData;
    }
}
