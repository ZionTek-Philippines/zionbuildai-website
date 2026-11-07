<?php

namespace Database\Factories;

use App\Models\PreviewPassword;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PreviewPassword>
 */
class PreviewPasswordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => fake()->company(),
            'password' => PreviewPassword::generate(),
            'is_active' => true,
            'expires_at' => null,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }

    public function expired(): static
    {
        return $this->state(['expires_at' => now()->subDay()]);
    }
}
