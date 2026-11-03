<?php

namespace Database\Factories;

use App\Models\DemoBooking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DemoBooking>
 */
class DemoBookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'contact_number' => '09'.fake()->numerify('#########'),
            'email' => fake()->safeEmail(),
            'company_info' => fake()->company().' — '.fake()->sentence(),
            'status' => 'new',
        ];
    }
}
