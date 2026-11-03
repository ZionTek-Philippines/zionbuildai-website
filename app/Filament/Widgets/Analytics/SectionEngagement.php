<?php

namespace App\Filament\Widgets\Analytics;

use Illuminate\Support\Facades\DB;

class SectionEngagement extends RankedListWidget
{
    public function getHeading(): string
    {
        return 'Time spent per homepage section';
    }

    public function getDescription(): ?string
    {
        return 'Average reading time per visit that reached the section. Idle time and hidden tabs are not counted.';
    }

    public function getRows(): array
    {
        $visits = $this->visitsInPeriod()->count();

        return $this->eventsInPeriod('section_time')
            ->select('section', DB::raw('SUM(value) as total_seconds'), DB::raw('COUNT(DISTINCT site_visit_id) as readers'))
            ->where('path', '/')
            ->whereNotNull('section')
            ->groupBy('section')
            ->get()
            ->map(fn ($row): array => [
                'label' => $row->section,
                'sublabel' => 'Read in '.$this->percentage((int) $row->readers, $visits).' of visits',
                'value' => $row->total_seconds / max(1, $row->readers),
                'display' => $this->formatDuration($row->total_seconds / max(1, $row->readers)),
                'url' => $this->visitLogUrl(['event' => ['type' => 'section_time', 'section' => $row->section, 'path' => '/']]),
            ])
            ->sortByDesc('value')
            ->values()
            ->all();
    }
}
