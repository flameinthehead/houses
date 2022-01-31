<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'nullable|string',
            'price_from' => 'nullable|integer',
            'price_to' => 'nullable|integer',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'storeys' => 'nullable|integer',
            'garages' => 'nullable|integer',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if(
                !empty($this->price_from) && !empty($this->price_to)
                && $this->price_from > $this->price_to
            ){
                $validator->errors()->add('price_range', 'From price cannot bigger than to price!');
            }
        });
    }
}
