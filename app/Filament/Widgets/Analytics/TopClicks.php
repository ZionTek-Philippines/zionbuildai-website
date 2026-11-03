<?php

namespace App\Filament\Widgets\Analytics;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TopClicks extends RankedListWidget
{
    public function getHeading(): string
    {
        return 'Most clicked buttons and links';
    }

    public function getDescription(): ?string
    {
        return 'Grouped by button text and where on the page it was clicked. FAQ questions have their own card.';
    }

    public function getRows(): array
    {
        return $this->eventsInPeriod('click')
            ->where(fn (Builder $query) => $query->whereNull('section')->orWhere('section', '!=', 'FAQ'))
            ->select('label', 'section', DB::raw('COUNT(*) as clicks'))
            ->groupBy('label', 'section')
            ->orderByDesc('clicks')
            ->limit(12)
            ->get()
            ->map(fn ($row): array => [
                'label' => $row->label ?? 'Unlabelled',
                'sublabel' => $row->section ? "in {$row->section}" : 'in the live demo',
                'value' => (int) $row->clicks,
                'display' => number_format($row->clicks),
                'url' => $this->visitLogUrl(['event' => ['type' => 'click', 'label' => $row->label, 'section' => $row->section]]),
            ])
            ->all();
    }
}
