<?php

namespace App\Filament\Widgets\Analytics;

use App\Filament\Widgets\Analytics\Concerns\InteractsWithAnalyticsPeriod;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DevicesChart extends ChartWidget
{
    use InteractsWithAnalyticsPeriod;

    protected static bool $isDiscovered = false;

    protected ?string $heading = 'Devices';

    protected ?string $pollingInterval = null;

    protected ?string $maxHeight = '260px';

    /**
     * @var array<string, string>
     */
    private const DEVICE_COLORS = [
        'mobile' => '#4f46e5',
        'desktop' => '#10b981',
        'tablet' => '#f59e0b',
    ];

    public function getDescription(): ?string
    {
        $topBrowser = $this->visitsInPeriod()
            ->select('browser', DB::raw('COUNT(*) as visits'))
            ->groupBy('browser')
            ->orderByDesc('visits')
            ->first();

        return $topBrowser ? "Most used browser: {$topBrowser->browser}" : null;
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $devices = $this->visitsInPeriod()
            ->select('device', DB::raw('COUNT(*) as visits'))
            ->groupBy('device')
            ->orderByDesc('visits')
            ->pluck('visits', 'device');

        return [
            'datasets' => [
                [
                    'data' => $devices->values()->all(),
                    'backgroundColor' => $devices->keys()->map(fn (string $device): string => self::DEVICE_COLORS[$device] ?? '#94a3b8')->all(),
                ],
            ],
            'labels' => $devices->keys()->map(fn (string $device): string => ucfirst($device))->all(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => ['x' => ['display' => false], 'y' => ['display' => false]],
        ];
    }
}
