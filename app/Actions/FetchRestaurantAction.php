<?php

namespace App\Actions;

use App\Transformers\GoogleMapTransformer;
use GuzzleHttp\Client;

class FetchRestaurantAction
{
    public function execute(array|null $filters): array
    {
        $transformedData = [];
        $location = $this->getLocationByPlace($filters['search']);
        if ($location == null) {
            return [];
        }
        $client = new Client();
        $response = $client->get("https://maps.googleapis.com/maps/api/place/nearbysearch/json", [
            'query' => [
                'location' => $location,
                'radius' => 1500,
                'types' => 'restaurant',
                'key' => config('services.google.maps_api_key'),
            ],
        ]);
        $rawData = json_decode($response->getBody());
        if ($rawData->status === 'INVALID_REQUEST') {
            return [];
        }
        $transformedData = fractal($rawData->results, new GoogleMapTransformer())->toArray()['data'];
        return $transformedData;
    }

    private function getLocationByPlace(string|null $search): null|string
    {
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
}
