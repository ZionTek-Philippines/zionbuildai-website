<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\DemoBooking;

class ConversionFunnel extends RankedListWidget
{
    public function getHeading(): string
    {
        return 'Booking funnel';
    }

    public function getDescription(): ?string
    {
        return 'Where visitors drop off on the way to booking a demo.';
    }

    public function getRows(): array
    {
        $visits = $this->visitsInPeriod()->count();

        $steps = [
            ['Visited the site', $visits, $this->visitLogUrl()],
            [
                'Scrolled halfway down the homepage',
                $this->eventsInPeriod('scroll')->where('path', '/')->where('value', '>=', 50)->distinct()->count('site_visit_id'),
                $this->visitLogUrl(['event' => ['type' => 'scroll', 'path' => '/', 'min_value' => 50]]),
            ],
            [
                'Reached the "Book a Demo" section',
                $this->eventsInPeriod('section_time')->where('section', 'Book a Demo')->distinct()->count('site_visit_id'),
                $this->visitLogUrl(['event' => ['type' => 'section_time', 'section' => 'Book a Demo']]),
            ],
            [
                'Started filling in the form',
                $this->eventsInPeriod('form_start')->where('label', 'Book a Demo')->distinct()->count('site_visit_id'),
                $this->visitLogUrl(['event' => ['type' => 'form_start', 'label' => 'Book a Demo']]),
            ],
            ['Booked a demo', DemoBooking::where('created_at', '>=', $this->periodStart())->count(), $this->bookingsUrl()],
        ];

        return array_map(fn (array $step): array => [
            'label' => $step[0],
            'sublabel' => $this->percentage($step[1], $visits).' of visits',
            'value' => $step[1],
            'display' => number_format($step[1]),
            'url' => $step[2],
        ], $steps);
    }
}
