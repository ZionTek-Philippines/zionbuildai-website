<?php

namespace App\Filament\Widgets\Analytics\Concerns;

use App\Filament\Pages\SiteAnalytics;
use App\Filament\Resources\DemoBookings\DemoBookingResource;
use App\Filament\Resources\DemoLeads\DemoLeadResource;
use App\Filament\Resources\SiteVisits\SiteVisitResource;
use App\Models\SiteEvent;
use App\Models\SiteVisit;
use Carbon\CarbonImmutable;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Reads the Site Analytics period filter. Days start at midnight Philippine time.
 */
trait InteractsWithAnalyticsPeriod
{
    use InteractsWithPageFilters;

    protected function reportingTimezone(): string
    {
        return SiteAnalytics::TIMEZONE;
    }

    protected function periodKey(): string
    {
        $period = (string) ($this->pageFilters['period'] ?? '7');

        return array_key_exists($period, SiteAnalytics::PERIODS) ? $period : '7';
    }

    protected function periodStart(): CarbonImmutable
    {
        return SiteAnalytics::periodStart($this->periodKey());
    }

    /**
     * Link to the Visitor Log, filtered to this period plus the given table filters.
     *
     * @param  array<string, array<string, mixed>>  $filters
     */
    protected function visitLogUrl(array $filters = []): string
    {
        return SiteVisitResource::getUrl('index', ['filters' => ['period' => ['value' => $this->periodKey()], ...$filters]]);
    }

    /**
     * @param  array<string, array<string, mixed>>  $filters
     */
    protected function bookingsUrl(array $filters = []): string
    {
        return DemoBookingResource::getUrl('index', ['filters' => ['period' => ['value' => $this->periodKey()], ...$filters]]);
    }

    protected function leadsUrl(): string
    {
        return DemoLeadResource::getUrl('index', ['filters' => ['period' => ['value' => $this->periodKey()]]]);
    }

    /**
     * The equally long stretch just before the current period, for "vs previous" comparisons.
     *
     * @return array{0: CarbonImmutable, 1: CarbonImmutable}
     */
    protected function previousPeriod(): array
    {
        $start = $this->periodStart();
        $length = $start->diffInSeconds(CarbonImmutable::now());

        return [$start->subSeconds((int) $length), $start];
    }

    /**
     * @return Builder<SiteVisit>
     */
    protected function visitsInPeriod(): Builder
    {
        return SiteVisit::query()->where('started_at', '>=', $this->periodStart());
    }

    /**
     * @return Builder<SiteEvent>
     */
    protected function eventsInPeriod(string $type): Builder
    {
        return SiteEvent::query()
            ->where('type', $type)
            ->where('created_at', '>=', $this->periodStart());
    }

    /**
     * Visit counts per traffic source, busiest first.
     *
     * @return Collection<string, int>
     */
    protected function visitsBySource(): Collection
    {
        return $this->visitsInPeriod()
            ->select('utm_source', 'referrer_host', DB::raw('COUNT(*) as visits'))
            ->groupBy('utm_source', 'referrer_host')
            ->get()
            ->groupBy(fn (SiteVisit $row): string => $row->source())
            ->map(fn (Collection $rows): int => (int) $rows->sum('visits'))
            ->sortDesc();
    }

    protected function formatDuration(float|int $seconds): string
    {
        $seconds = (int) round($seconds);

        return $seconds < 60 ? "{$seconds}s" : sprintf('%dm %02ds', intdiv($seconds, 60), $seconds % 60);
    }

    protected function percentage(int $part, int $whole): string
    {
        return $whole > 0 ? round($part / $whole * 100, 1).'%' : '0%';
    }
}
