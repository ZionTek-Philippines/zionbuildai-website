<?php

namespace App\Filament\Widgets\Analytics;

use App\Filament\Widgets\Analytics\Concerns\InteractsWithAnalyticsPeriod;
use Filament\Widgets\Widget;

/**
 * A "top N" list with proportional bars, in the style of Google Analytics report cards.
 */
abstract class RankedListWidget extends Widget
{
    use InteractsWithAnalyticsPeriod;

    protected static bool $isDiscovered = false;

    protected string $view = 'filament.widgets.analytics.ranked-list';

    abstract public function getHeading(): string;

    public function getDescription(): ?string
    {
        return null;
    }

    /**
     * Rows in display order. `value` sizes the bar, `display` is the text shown on the right,
     * and `url` (optional) opens the records behind the row, e.g. the matching visits in the Visitor Log.
     *
     * @return list<array{label: string, sublabel?: string|null, value: int|float, display: string, url?: string|null}>
     */
    abstract public function getRows(): array;
}
