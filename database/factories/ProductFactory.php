<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class ProductFactory extends Factory
{

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(rand(1,6)),
            'price' => $this->faker->randomFloat(2, 10, 10000),
        ];
    }
}
