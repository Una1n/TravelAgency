<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Travel>
 */
class TravelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->uuid(),
            'is_public' => false,
            'slug' => fake()->slug(),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'number_of_days' => fake()->numberBetween(1, 25),
        ];
    }
}
