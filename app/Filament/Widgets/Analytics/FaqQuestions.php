<?php

namespace App\Filament\Widgets\Analytics;

use Illuminate\Support\Facades\DB;

class FaqQuestions extends RankedListWidget
{
    public function getHeading(): string
    {
        return 'FAQ questions opened';
    }

    public function getDescription(): ?string
    {
        return 'What visitors want to know or worry about, a good guide for homepage copy.';
    }

    public function getRows(): array
    {
        return $this->eventsInPeriod('click')
            ->where('section', 'FAQ')
            ->select('label', DB::raw('COUNT(*) as opens'), DB::raw('COUNT(DISTINCT site_visit_id) as visits'))
            ->groupBy('label')
            ->orderByDesc('opens')
            ->limit(10)
            ->get()
            ->map(fn ($row): array => [
                'label' => $row->label ?? 'Unlabelled',
                'sublabel' => 'Opened in '.number_format($row->visits).' '.str('visit')->plural($row->visits),
                'value' => (int) $row->opens,
                'display' => number_format($row->opens),
                'url' => $this->visitLogUrl(['event' => ['type' => 'click', 'section' => 'FAQ', 'label' => $row->label]]),
            ])
            ->all();
    }
}
