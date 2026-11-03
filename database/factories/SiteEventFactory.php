<?php

namespace Database\Factories;

use App\Models\SiteEvent;
use App\Models\SiteVisit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SiteEvent>
 */
class SiteEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'site_visit_id' => SiteVisit::factory(),
            'type' => 'page_view',
            'path' => '/',
            'section' => null,
            'label' => 'ZionBuild AI',
            'value' => null,
            'created_at' => now(),
        ];
    }

    public function click(string $label, ?string $section = null): static
    {
        return $this->state(['type' => 'click', 'label' => $label, 'section' => $section]);
    }

    public function sectionTime(string $section, int $seconds): static
    {
        return $this->state(['type' => 'section_time', 'label' => $section, 'section' => $section, 'value' => $seconds]);
    }
}
