<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FetchRestaurantRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'search' => ['nullable'],
        ];
        return $rules;
    }
}
