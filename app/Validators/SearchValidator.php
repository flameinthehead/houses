<?php

namespace App\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchValidator
{
    const RULES = [
        'name' => 'nullable|string',
        'price_from' => 'nullable|integer',
        'price_to' => 'nullable|integer',
        'bedrooms' => 'nullable|integer',
        'bathrooms' => 'nullable|integer',
        'storeys' => 'nullable|integer',
        'garages' => 'nullable|integer',
    ];

    private $request;
    private $errorMessage;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function validate()
    {
        $validator = Validator::make($this->request->all(), self::RULES);

        $validator->after(function ($validator) {
            if(
                !empty($this->request->price_from) && !empty($this->request->price_to)
                && $this->request->price_from > $this->request->price_to
            ){
                $validator->errors()->add('price_range', 'From price cannot bigger than to price!');
            }
        });

        if($validator->fails()){
            $this->errorMessage = $validator->getMessageBag();
            return false;
        }

        return true;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}
