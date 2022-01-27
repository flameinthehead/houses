<?php

namespace App\Services;

use App\Entity\House;
use Illuminate\Http\Request;

class SearchService
{
    const ONLY_DIGITS_FIELD_LIST = [
        'bedrooms',
        'bathrooms',
        'storeys',
        'garages',
    ];

    public function search(Request $request)
    {
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
            'houses' => $builder->get()->all(),
        ]);
    }
}
