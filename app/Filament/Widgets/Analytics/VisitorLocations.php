<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\SiteVisit;
use Illuminate\Support\Facades\DB;

class VisitorLocations extends RankedListWidget
{
    public function getHeading(): string
    {
        return 'Visitor locations';
    }

    public function getDescription(): ?string
    {
        return 'Country from the browser\'s time zone; city and region when the site runs behind Cloudflare. No IP addresses are stored.';
    }

    public function getRows(): array
    {
        $visits = $this->visitsInPeriod()->count();

        return $this->visitsInPeriod()
            ->select('region', 'country', 'timezone', DB::raw('COUNT(*) as visits'))
            ->groupBy('region', 'country', 'timezone')
            ->get()
            ->groupBy(fn (SiteVisit $row): string => $row->locationName())
            ->map(fn ($rows, string $location): array => [
                'label' => $location,
                'sublabel' => $this->percentage((int) $rows->sum('visits'), $visits).' of visits',
                'value' => (int) $rows->sum('visits'),
                'display' => number_format($rows->sum('visits')),
                'url' => $this->visitLogUrl(['location' => ['value' => $location]]),
            ])
            ->sortByDesc('value')
            ->take(10)
            ->values()
            ->all();
    }
}
