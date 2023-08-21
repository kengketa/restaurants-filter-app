<?php

namespace App\Transformers;


use Illuminate\Support\Collection;
use League\Fractal\TransformerAbstract;
use GuzzleHttp\Client;

class GoogleMapTransformer extends TransformerAbstract
{
    public function transform(object $restaurant)
    {
        $photo = null;
        if (isset($restaurant->photos)) {
            $photo = $restaurant->photos[0];
        }
        $data = [
            'name' => $restaurant->name,
            'photo' => $this->getImage($photo?->photo_reference),
            'address' => $restaurant->vicinity
        ];
        return $data;
    }

    private function getImage($photoReference): string|null
    {
        if ($photoReference == null) {
            return null;
        }
        $imageUrl = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photo_reference=$photoReference&key=" . config(
                'services.google.maps_api_key'
            );
        return $imageUrl;
    }

//    private function getPlaceDeatils(string $placeId)
//    {
//        $client = new Client();
//        $response = $client->get("https://maps.googleapis.com/maps/api/place/details/json", [
//            'query' => [
//                'key' => config('services.google.maps_api_key'),
//                'place_id' => $placeId,
//            ],
//        ]);
//        $data = json_decode($response->getBody());
//        dd($data);
//    }
}
