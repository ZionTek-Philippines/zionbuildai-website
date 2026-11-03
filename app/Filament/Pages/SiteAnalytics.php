<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\Analytics\AnalyticsOverview;
use App\Filament\Widgets\Analytics\BookingsBySource;
use App\Filament\Widgets\Analytics\BusiestTimes;
use App\Filament\Widgets\Analytics\ConversionFunnel;
use App\Filament\Widgets\Analytics\DemoDepth;
use App\Filament\Widgets\Analytics\DevicesChart;
use App\Filament\Widgets\Analytics\FaqQuestions;
use App\Filament\Widgets\Analytics\FormDropOff;
use App\Filament\Widgets\Analytics\SectionEngagement;
use App\Filament\Widgets\Analytics\TopClicks;
use App\Filament\Widgets\Analytics\TopPages;
use App\Filament\Widgets\Analytics\TrafficSources;
use App\Filament\Widgets\Analytics\VisitorLocations;
use App\Filament\Widgets\Analytics\VisitsChart;
use BackedEnum;
use Carbon\CarbonImmutable;
use Filament\Forms\Components\ToggleButtons;
use Filament\Pages\Dashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * First-party, anonymous analytics for the marketing site, collected by resources/js/site-analytics.js.
 */
class SiteAnalytics extends Dashboard
{
    use HasFiltersForm;

    /**
     * Reporting periods, keyed by filter value (number of days, or "today").
     *
     * @var array<string, string>
     */
    public const PERIODS = [
        'today' => 'Today',
        '7' => '7 days',
        '30' => '30 days',
        '90' => '90 days',
        '365' => '12 months',
    ];

    public const TIMEZONE = 'Asia/Manila';

    protected static string $routePath = 'analytics';

    protected static ?string $title = 'Site Analytics';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?int $navigationSort = 1;

    /**
     * Start of a reporting period in UTC. Days start at midnight Philippine time.
     */
    public static function periodStart(string $period): CarbonImmutable
    {
        $period = array_key_exists($period, self::PERIODS) ? $period : '7';
        $today = CarbonImmutable::now(self::TIMEZONE)->startOfDay();

        return ($period === 'today' ? $today : $today->subDays((int) $period - 1))->utc();
    }

    /**
     * The same period choices as a table filter, so drill-down lists match the card that was clicked.
     */
    public static function periodFilter(string $column): SelectFilter
    {
        return SelectFilter::make('period')
            ->label('Period')
            ->options(self::PERIODS)
            ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
                ? $query->where($column, '>=', self::periodStart($data['value']))
                : $query);
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                ToggleButtons::make('period')
                    ->hiddenLabel()
                    ->options(self::PERIODS)
                    ->default('7')
                    ->inline()
                    ->grouped(),
            ]);
    }

    public function getWidgets(): array
    {
        return [
            AnalyticsOverview::class,
            VisitsChart::class,
            ConversionFunnel::class,
            BookingsBySource::class,
            SectionEngagement::class,
            TopClicks::class,
            FormDropOff::class,
            FaqQuestions::class,
            DemoDepth::class,
            TopPages::class,
            TrafficSources::class,
            VisitorLocations::class,
            BusiestTimes::class,
            DevicesChart::class,
        ];
    }

    public function getColumns(): int|array
    {
        return ['md' => 2];
    }
}
