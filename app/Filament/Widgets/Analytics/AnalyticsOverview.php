<?php

namespace App\Filament\Widgets\Analytics;

use App\Filament\Widgets\Analytics\Concerns\InteractsWithAnalyticsPeriod;
use App\Models\DemoBooking;
use App\Models\SiteVisit;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AnalyticsOverview extends StatsOverviewWidget
{
    use InteractsWithAnalyticsPeriod;

    protected static bool $isDiscovered = false;

    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 'full';

    /**
     * Headline numbers for the period, also used by the weekly email report.
     *
     * @return array{visitors: int, previous_visitors: int, visits: int, previous_visits: int, returning_visits: int, page_views: int, demo_opens: int, bookings: int, average_duration: float}
     */
    public function metrics(): array
    {
        [$previousStart, $previousEnd] = $this->previousPeriod();
        $previousVisits = SiteVisit::query()->whereBetween('started_at', [$previousStart, $previousEnd]);

        return [
            'visitors' => $this->visitsInPeriod()->distinct()->count('visitor_id'),
            'previous_visitors' => (clone $previousVisits)->distinct()->count('visitor_id'),
            'visits' => $this->visitsInPeriod()->count(),
            'previous_visits' => $previousVisits->count(),
            'returning_visits' => $this->visitsInPeriod()->where('is_returning', true)->count(),
            'page_views' => $this->eventsInPeriod('page_view')->count(),
            'demo_opens' => $this->eventsInPeriod('page_view')->where('path', 'like', '/demo%')->distinct()->count('site_visit_id'),
            'bookings' => DemoBooking::where('created_at', '>=', $this->periodStart())->count(),
            'average_duration' => (float) ($this->visitsInPeriod()
                ->get(['started_at', 'last_seen_at'])
                ->avg(fn (SiteVisit $visit): int => (int) $visit->started_at->diffInSeconds($visit->last_seen_at)) ?? 0),
        ];
    }

    /**
     * "+12% vs previous period", or null when there is nothing to compare with.
     */
    public function changeDescription(int $current, int $previous): ?string
    {
        if ($previous === 0) {
            return null;
        }

        $change = round(($current - $previous) / $previous * 100);

        return ($change >= 0 ? '+' : '').$change.'% vs previous period';
    }

    protected function getStats(): array
    {
        $metrics = $this->metrics();
        $visits = $metrics['visits'];

        return [
            $this->comparedStat('Visitors', $metrics['visitors'], $metrics['previous_visitors'])
                ->icon(Heroicon::OutlinedUsers),
            $this->comparedStat('Visits', $visits, $metrics['previous_visits'])
                ->icon(Heroicon::OutlinedCursorArrowRays),
            Stat::make('Page views', number_format($metrics['page_views']))
                ->description($visits > 0 ? round($metrics['page_views'] / $visits, 1).' pages per visit' : 'No visits yet')
                ->icon(Heroicon::OutlinedEye),
            Stat::make('Avg. visit duration', $this->formatDuration($metrics['average_duration']))
                ->description($this->percentage($metrics['returning_visits'], $visits).' of visits are from returning visitors')
                ->icon(Heroicon::OutlinedClock),
            Stat::make('Opened the live demo', number_format($metrics['demo_opens']))
                ->description($this->percentage($metrics['demo_opens'], $visits).' of visits')
                ->icon(Heroicon::OutlinedPlayCircle),
            Stat::make('Demo bookings', number_format($metrics['bookings']))
                ->description($this->percentage($metrics['bookings'], $visits).' conversion rate')
                ->color('success')
                ->icon(Heroicon::OutlinedCalendarDays),
        ];
    }

    private function comparedStat(string $label, int $current, int $previous): Stat
    {
        $stat = Stat::make($label, number_format($current));
        $change = $this->changeDescription($current, $previous);

        if ($change === null) {
            return $stat->description('No data for the previous period');
        }

        $isUp = $current >= $previous;

        return $stat
            ->description($change)
            ->descriptionIcon($isUp ? Heroicon::ArrowTrendingUp : Heroicon::ArrowTrendingDown)
            ->color($isUp ? 'success' : 'danger');
    }
}
