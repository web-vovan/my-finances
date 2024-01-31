<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class CostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price' => rand(100, 3000),
            'category_id' => rand(1, 4),
            'date' => Carbon::now()->subDays(rand(1, 60)),
            'comment' => fake()->text(10),
            'user_id' => rand(1, 2)
        ];
    }
}
