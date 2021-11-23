<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name_products' => $this->faker->sentence(2),
            'quantity' => $this->faker->numberBetween(5, 100),
            'price' => $this->faker->randomElement([2, 4, 8, 15, 30]),
            'total' => $this->faker->numberBetween(5, 100),
        ];
    }
}
