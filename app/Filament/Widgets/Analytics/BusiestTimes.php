<?php

namespace App\Filament\Widgets\Analytics;

use App\Filament\Widgets\Analytics\Concerns\InteractsWithAnalyticsPeriod;
use App\Models\SiteVisit;
use Filament\Widgets\Widget;

/**
 * Day-of-week by hour-of-day heatmap of visit starts, in Philippine time.
 */
class BusiestTimes extends Widget
{
    use InteractsWithAnalyticsPeriod;

    /**
     * ISO day number (1 = Monday) to short name.
     *
     * @var array<int, string>
     */
    public const DAYS = [1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun'];

    protected static bool $isDiscovered = false;

    protected string $view = 'filament.widgets.analytics.busiest-times';

    protected int|string|array $columnSpan = 'full';

    /**
     * @return array<int, array<int, int>> Visit counts keyed by ISO day, then hour (0-23).
     */
    public function getGrid(): array
    {
        $grid = array_fill_keys(array_keys(self::DAYS), array_fill(0, 24, 0));

        $this->visitsInPeriod()->get(['started_at'])->each(function (SiteVisit $visit) use (&$grid): void {
            $local = $visit->started_at->setTimezone($this->reportingTimezone());
            $grid[$local->isoWeekday()][$local->hour]++;
        });

        return $grid;
    }

    public function hourLabel(int $hour): string
    {
        return match (true) {
            $hour === 0 => '12am',
            $hour < 12 => "{$hour}am",
            $hour === 12 => '12pm',
            default => ($hour - 12).'pm',
        };
    }

    /**
     * @param  array<int, array<int, int>>  $grid
     */
    public function busiestSlot(array $grid): ?string
    {
        $best = null;

        foreach ($grid as $day => $hours) {
            foreach ($hours as $hour => $visits) {
                if ($visits > 0 && ($best === null || $visits > $best[2])) {
                    $best = [$day, $hour, $visits];
                }
            }
        }

        return $best ? self::DAYS[$best[0]].' around '.$this->hourLabel($best[1]).' ('.$best[2].' '.str('visit')->plural($best[2]).')' : null;
    }
}
