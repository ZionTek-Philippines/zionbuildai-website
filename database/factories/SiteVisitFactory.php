<?php

namespace Database\Factories;

use App\Models\SiteVisit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SiteVisit>
 */
class SiteVisitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startedAt = now()->subMinutes(fake()->numberBetween(5, 60 * 24 * 6));

        return [
            'uuid' => fake()->uuid(),
            'visitor_id' => fake()->uuid(),
            'landing_path' => '/',
            'referrer_host' => fake()->randomElement([null, 'www.google.com', 'm.facebook.com']),
            'device' => fake()->randomElement(['mobile', 'desktop', 'tablet']),
            'browser' => fake()->randomElement(['Chrome', 'Safari', 'Facebook app']),
            'is_returning' => false,
            'started_at' => $startedAt,
            'last_seen_at' => $startedAt->copy()->addMinutes(fake()->numberBetween(0, 10)),
        ];
    }
}
