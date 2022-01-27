<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\HouseFactory;

class House extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return HouseFactory::new();
    }

    public function scopePriceRange(Builder $builder, $min = null, $max = null)
    {
        if($min > 0){
            $builder->where('price', '>=', $min);
        }

        if($max > 0){
            $builder->where('price', '<=', $max);
        }

        return $builder;
    }
}
