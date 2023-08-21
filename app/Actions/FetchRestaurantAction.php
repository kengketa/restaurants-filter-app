<?php

namespace App\Actions;

use App\Transformers\GoogleMapTransformer;
use GuzzleHttp\Client;

class FetchRestaurantAction
{
    public function execute(array|null $filters): array
    {
        $transformedData = [];
        $location = $this->getLocationByPlace($filters['search']); // get location by user's search text
        if ($location == null) {
            return [];
        }
        $rawData = $this->getRestaurantsByLocation(
            $location,
            $filters['nextPageToken'] ?? null
        ); // use the location to find restaurant nearby the keyword
        if (empty($rawData)) {
            return [];
        }
        $transformedData = fractal($rawData->results, new GoogleMapTransformer())->toArray();
        $transformedData['meta']['next_page_token'] = isset($rawData?->next_page_token) ? $rawData?->next_page_token : null;
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

    private function getRestaurantsByLocation(string $location, string|null $nextPageToken)
    {
        $client = new Client();
        $response = $client->get("https://maps.googleapis.com/maps/api/place/nearbysearch/json", [
            'query' => [
                'location' => $location,
                'radius' => 1500,
                'types' => 'restaurant',
                'key' => config('services.google.maps_api_key'),
                'pagetoken' => $nextPageToken
            ],
        ]);
        $rawData = json_decode($response->getBody());
        if ($rawData->status === 'INVALID_REQUEST') {
            return [];
        }
        return $rawData;
    }
}
