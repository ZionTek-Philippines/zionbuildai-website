<?php

namespace Database\Factories;

use App\Models\DemoLead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DemoLead>
 */
class DemoLeadFactory extends Factory
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
            'mobile' => '09'.fake()->numerify('#########'),
            'company' => fake()->company(),
            'role' => fake()->randomElement(array_keys(DemoLead::ROLES)),
            'unlocked_from' => 'demo.finances',
        ];
    }
}
