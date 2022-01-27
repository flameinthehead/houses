<?php

namespace Database\Factories;

use App\Entity\House;
use Illuminate\Database\Eloquent\Factories\Factory;

class HouseFactory extends Factory
{
    protected $model = House::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'price' => rand(200000, 600000),
            'bedrooms' => rand(3, 5),
            'bathrooms' => rand(2, 3),
            'storeys' => rand(1, 2),
            'garages' => rand(1, 2),
        ];
    }
}
