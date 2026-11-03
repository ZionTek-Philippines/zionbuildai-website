<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\SiteEvent;
use Illuminate\Support\Facades\DB;

class TopPages extends RankedListWidget
{
    public function getHeading(): string
    {
        return 'Pages and demo modules viewed';
    }

    public function getRows(): array
    {
        return $this->eventsInPeriod('page_view')
            ->select('path', DB::raw('COUNT(*) as views'), DB::raw('COUNT(DISTINCT site_visit_id) as visits'))
            ->groupBy('path')
            ->orderByDesc('views')
            ->limit(12)
            ->get()
            ->map(fn ($row): array => [
                'label' => SiteEvent::pageName($row->path),
                'sublabel' => $row->path.' · '.number_format($row->visits).' '.str('visit')->plural($row->visits),
                'value' => (int) $row->views,
                'display' => number_format($row->views),
                'url' => $this->visitLogUrl(['event' => ['type' => 'page_view', 'path' => $row->path]]),
            ])
            ->all();
    }
}
