<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FetchRestaurantRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'search' => ['nullable'],
            'nextPageToken' => ['nullable']
        ];
        return $rules;
    }
}
