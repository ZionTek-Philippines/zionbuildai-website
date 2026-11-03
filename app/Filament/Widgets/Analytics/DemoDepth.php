<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\DemoLead;
use Illuminate\Support\Facades\DB;

class DemoDepth extends RankedListWidget
{
    /**
     * How It Works, Command Center, Field Reports, AI Project Monitoring, Finances, Payroll, Client Billing.
     */
    private const DEMO_MODULES = 7;

    public function getHeading(): string
    {
        return 'Live demo depth';
    }

    public function getDescription(): ?string
    {
        return 'How far visitors explore the live demo, out of '.self::DEMO_MODULES.' modules.';
    }

    public function getRows(): array
    {
        $modulesPerVisit = $this->eventsInPeriod('page_view')
            ->where('path', 'like', '/demo%')
            ->select('site_visit_id', DB::raw('COUNT(DISTINCT path) as modules'))
            ->groupBy('site_visit_id')
            ->pluck('modules')
            ->map(fn ($modules): int => (int) $modules);

        $opened = $modulesPerVisit->count();

        $atLeast = fn (int $minimum): array => [
            $modulesPerVisit->filter(fn (int $modules): bool => $modules >= $minimum)->count(),
            $this->visitLogUrl(['demo_modules' => ['value' => (string) $minimum]]),
        ];

        $steps = [
            ['Opened the live demo', ...$atLeast(1)],
            ['Explored 2 or more modules', ...$atLeast(2)],
            ['Explored 4 or more modules', ...$atLeast(4)],
            ['Explored all '.self::DEMO_MODULES.' modules', ...$atLeast(self::DEMO_MODULES)],
            ['Unlocked the full demo (left their details)', DemoLead::where('created_at', '>=', $this->periodStart())->count(), $this->leadsUrl()],
        ];

        return array_map(fn (array $step): array => [
            'label' => $step[0],
            'sublabel' => $this->percentage($step[1], $opened).' of demo visits',
            'value' => $step[1],
            'display' => number_format($step[1]),
            'url' => $step[2],
        ], $steps);
    }
}
