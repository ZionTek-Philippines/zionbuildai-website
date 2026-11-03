<?php

namespace App\Filament\Pages;

use App\Filament\Resources\SiteVisits\SiteVisitResource;
use App\Models\DemoBooking;
use App\Models\SiteEvent;
use App\Models\SiteVisit;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;

/**
 * The live homepage with the analytics drawn on top of it: glowing click hotspots, section reading heat,
 * scroll-depth lines and form drop-off badges. Drawn by resources/js/visual-analytics.js.
 */
class VisualAnalytics extends Page
{
    protected string $view = 'filament.pages.visual-analytics';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFire;

    protected static ?string $title = 'Visual Analytics';

    protected static ?int $navigationSort = 2;

    protected Width|string|null $maxContentWidth = Width::Full;

    #[Url]
    public string $period = '30';

    /**
     * Everything the overlay needs, limited to homepage activity in the selected period.
     *
     * @return array{
     *     period: string,
     *     homepageVisits: int,
     *     clicks: list<array{label: string, section: string, clicks: int, visits: int, url: string}>,
     *     sections: list<array{name: string, averageSeconds: int, readers: int, url: string}>,
     *     scroll: list<array{milestone: int, visits: int, url: string}>,
     *     fields: list<array{label: string, focused: int, stopped: int, url: string}>
     * }
     */
    public function getVisualData(): array
    {
        $period = array_key_exists($this->period, SiteAnalytics::PERIODS) ? $this->period : '30';
        $start = SiteAnalytics::periodStart($period);
        $homepageEvents = fn (string $type): Builder => SiteEvent::query()
            ->where('type', $type)
            ->where('path', '/')
            ->where('created_at', '>=', $start);
        $visitLog = fn (array $filters): string => SiteVisitResource::getUrl('index', ['filters' => ['period' => ['value' => $period], ...$filters]]);

        return [
            'period' => SiteAnalytics::PERIODS[$period],
            'homepageVisits' => $homepageEvents('page_view')->distinct()->count('site_visit_id'),
            'clicks' => $homepageEvents('click')
                ->whereNotNull('section')
                ->select('label', 'section', DB::raw('COUNT(*) as clicks'), DB::raw('COUNT(DISTINCT site_visit_id) as visits'))
                ->groupBy('label', 'section')
                ->orderByDesc('clicks')
                ->get()
                ->map(fn ($row): array => [
                    'label' => (string) $row->label,
                    'section' => $row->section,
                    'clicks' => (int) $row->clicks,
                    'visits' => (int) $row->visits,
                    'url' => $visitLog(['event' => ['type' => 'click', 'label' => $row->label, 'section' => $row->section]]),
                ])
                ->all(),
            'sections' => $homepageEvents('section_time')
                ->whereNotNull('section')
                ->select('section', DB::raw('SUM(value) as total_seconds'), DB::raw('COUNT(DISTINCT site_visit_id) as readers'))
                ->groupBy('section')
                ->get()
                ->map(fn ($row): array => [
                    'name' => $row->section,
                    'averageSeconds' => (int) round($row->total_seconds / max(1, $row->readers)),
                    'readers' => (int) $row->readers,
                    'url' => $visitLog(['event' => ['type' => 'section_time', 'section' => $row->section, 'path' => '/']]),
                ])
                ->all(),
            'scroll' => collect([25, 50, 75, 100])
                ->map(fn (int $milestone): array => [
                    'milestone' => $milestone,
                    'visits' => $homepageEvents('scroll')->where('value', '>=', $milestone)->distinct()->count('site_visit_id'),
                    'url' => $visitLog(['event' => ['type' => 'scroll', 'path' => '/', 'min_value' => $milestone]]),
                ])
                ->all(),
            'fields' => $this->formFields($start, $visitLog),
        ];
    }

    /**
     * How many visits clicked into each booking form field, and how many gave up with it as their last field.
     *
     * @param  callable(array<string, array<string, mixed>>): string  $visitLog
     * @return list<array{label: string, focused: int, stopped: int, url: string}>
     */
    private function formFields(\DateTimeInterface $start, callable $visitLog): array
    {
        $fieldEvents = SiteEvent::query()
            ->where('type', 'form_field')
            ->where('section', 'Book a Demo')
            ->where('created_at', '>=', $start);

        $bookedVisitIds = DemoBooking::whereNotNull('site_visit_id')->pluck('site_visit_id');

        $stoppedAt = SiteEvent::whereIn('id', (clone $fieldEvents)
            ->whereNotIn('site_visit_id', $bookedVisitIds)
            ->selectRaw('MAX(id)')
            ->groupBy('site_visit_id'))
            ->pluck('label')
            ->countBy();

        return (clone $fieldEvents)
            ->select('label', DB::raw('COUNT(DISTINCT site_visit_id) as visits'))
            ->groupBy('label')
            ->get()
            ->map(fn ($row): array => [
                'label' => (string) $row->label,
                'focused' => (int) $row->visits,
                'stopped' => (int) ($stoppedAt[$row->label] ?? 0),
                'url' => $visitLog(['stopped_at' => ['field' => $row->label]]),
            ])
            ->all();
    }

    /**
     * Visits in the period, for the toolbar summary.
     */
    public function getTotalVisits(): int
    {
        return SiteVisit::where('started_at', '>=', SiteAnalytics::periodStart($this->period))->count();
    }
}
