<?php

namespace App\Actions;

use GuzzleHttp\Client;

class FetchRestaurantAction
{
    public function execute(array|null $filters)
    {
        $location = $this->getLocationByAddress();
        $client = new Client();
        $response = $client->get("https://maps.googleapis.com/maps/api/place/nearbysearch/json", [
            'query' => [
                'location' => $location,
                'radius' => 1500,
                'types' => 'restaurant',
                'key' => config('services.google.maps_api_key'),
            ],
        ]);
        $data = json_decode($response->getBody());
        dd($data);
    }

    private function getLocationByAddress()
    {
        $client = new Client();
        $geocodingUrl = 'https://maps.googleapis.com/maps/api/geocode/json';
        $geocodingParams = [
            'query' => [
                'address' => 'Bang Sue',
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
        return $latitude . ',' . $longitude;
    }
}
