<?php

namespace App\Filament\Widgets\Analytics;

class TrafficSources extends RankedListWidget
{
    public function getHeading(): string
    {
        return 'Where visitors come from';
    }

    public function getDescription(): ?string
    {
        return 'Campaign tag (utm_source) when present, otherwise the referring website.';
    }

    public function getRows(): array
    {
        $visits = $this->visitsInPeriod()->count();

        return $this->visitsBySource()
            ->map(fn (int $sourceVisits, string $source): array => [
                'label' => $source,
                'sublabel' => $this->percentage($sourceVisits, $visits).' of visits',
                'value' => $sourceVisits,
                'display' => number_format($sourceVisits),
                'url' => $this->visitLogUrl(['source' => ['value' => $source]]),
            ])
            ->take(10)
            ->values()
            ->all();
    }
}
