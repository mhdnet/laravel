<?php

namespace Database\Factories;

use App\Constants\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return array_filter([
            'value' => fake()->boolean(90) ? fake()->numberBetween(50, 200) * 1000 : null,
            'fare' => fake()->boolean(10) ? fake()->numberBetween(10, 20) * 1000 : null,
            'status' => fake()->randomElement([OrderStatus::Received, OrderStatus::Shipped, OrderStatus::Rejected, OrderStatus::Shipping,]),
        ]);
    }
}
