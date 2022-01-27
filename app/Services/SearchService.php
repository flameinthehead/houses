<?php

namespace App\Services;

use App\Entity\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchService
{
    const ONLY_DIGITS_FIELD_LIST = [
        'bedrooms',
        'bathrooms',
        'storeys',
        'garages',
    ];

    const VALIDATION_RULES = [
        'name' => 'nullable|string',
        'price_from' => 'nullable|integer',
        'price_to' => 'nullable|integer',
        'bedrooms' => 'nullable|integer',
        'bathrooms' => 'nullable|integer',
        'storeys' => 'nullable|integer',
        'garages' => 'nullable|integer',
    ];

    public function search(Request $request)
    {
        if($errorMessage = $this->validate($request)){
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'data' => [],
            ]);
        }

        $builder = House::query();
        if($request->name){
            $builder->where('name', 'like', '%'.$request->name.'%');
        }

        foreach(self::ONLY_DIGITS_FIELD_LIST as $fieldName){
            if(!$request->{$fieldName}){
                continue;
            }

            $builder->where($fieldName, $request->{$fieldName});
        }

        if(!empty($request->price_from) || !empty($request->price_to)){
            $builder->priceRange($request->price_from, $request->price_to);
        }

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => $builder->get()->all(),
        ]);
    }

    private function validate(Request $request)
    {
        $validator = Validator::make($request->all(), self::VALIDATION_RULES);

        $validator->after(function ($validator) use ($request) {
            if(
                !empty($request->price_from) && !empty($request->price_to)
                && $request->price_from > $request->price_to
            ){
                $validator->errors()->add('price_range', 'From price cannot bigger than to price!');
            }
        });

        if($validator->fails()){
            return $validator->getMessageBag();
        }
    }
}
