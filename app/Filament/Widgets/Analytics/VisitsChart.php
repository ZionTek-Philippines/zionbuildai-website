<?php

namespace App\Filament\Widgets\Analytics;

use App\Filament\Widgets\Analytics\Concerns\InteractsWithAnalyticsPeriod;
use App\Models\SiteVisit;
use Carbon\CarbonImmutable;
use Filament\Widgets\ChartWidget;

class VisitsChart extends ChartWidget
{
    use InteractsWithAnalyticsPeriod;

    protected static bool $isDiscovered = false;

    protected ?string $heading = 'Visitors over time';

    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '280px';

    protected function getType(): string
    {
        return 'line';
    }

    /**
     * Hourly buckets for "Today", daily buckets otherwise. Grouped in PHP so it works on any database.
     */
    protected function getData(): array
    {
        $isHourly = $this->periodKey() === 'today';
        $format = $isHourly ? 'ga' : 'M j';
        $start = $this->periodStart()->setTimezone($this->reportingTimezone());
        $buckets = [];

        for ($slot = $start; $slot->lessThanOrEqualTo(CarbonImmutable::now($this->reportingTimezone())); $slot = $isHourly ? $slot->addHour() : $slot->addDay()) {
            $buckets[$slot->format($format)] = ['visits' => 0, 'visitors' => []];
        }

        $this->visitsInPeriod()->get(['visitor_id', 'started_at'])->each(function (SiteVisit $visit) use (&$buckets, $format): void {
            $key = $visit->started_at->setTimezone($this->reportingTimezone())->format($format);

            if (isset($buckets[$key])) {
                $buckets[$key]['visits']++;
                $buckets[$key]['visitors'][$visit->visitor_id] = true;
            }
        });

        return [
            'datasets' => [
                [
                    'label' => 'Visitors',
                    'data' => array_values(array_map(fn (array $bucket): int => count($bucket['visitors']), $buckets)),
                    'borderColor' => '#4f46e5',
                    'backgroundColor' => 'rgba(79, 70, 229, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Visits',
                    'data' => array_values(array_column($buckets, 'visits')),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'transparent',
                    'tension' => 0.3,
                ],
            ],
            'labels' => array_keys($buckets),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => ['y' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]]],
        ];
    }
}
