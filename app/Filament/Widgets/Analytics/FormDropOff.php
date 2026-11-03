<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\DemoBooking;
use App\Models\SiteEvent;
use Illuminate\Support\Collection;

class FormDropOff extends RankedListWidget
{
    private const FORM = 'Book a Demo';

    /**
     * @var array{0: Collection<int, int>, 1: Collection<int, int>}|null
     */
    private ?array $visitIds = null;

    public function getHeading(): string
    {
        return 'Where people give up on the form';
    }

    public function getDescription(): ?string
    {
        [$started, $booked] = $this->startedAndBookedVisitIds();

        if ($started->isEmpty()) {
            return 'Nobody has started the booking form in this period yet.';
        }

        return number_format($started->count()).' started the form and '.number_format($booked->count()).' booked. The last field each of the others clicked into:';
    }

    public function getRows(): array
    {
        [$started, $booked] = $this->startedAndBookedVisitIds();
        $abandoned = $started->diff($booked);

        if ($abandoned->isEmpty()) {
            return [];
        }

        $lastFieldEventIds = SiteEvent::query()
            ->where('type', 'form_field')
            ->where('section', self::FORM)
            ->whereIn('site_visit_id', $abandoned)
            ->selectRaw('MAX(id) as id')
            ->groupBy('site_visit_id')
            ->pluck('id');

        return SiteEvent::whereIn('id', $lastFieldEventIds)
            ->pluck('label')
            ->countBy()
            ->sortDesc()
            ->map(fn (int $count, string $field): array => [
                'label' => "Stopped at: {$field}",
                'sublabel' => $this->percentage($count, $abandoned->count()).' of drop-offs',
                'value' => $count,
                'display' => number_format($count),
                'url' => $this->visitLogUrl(['stopped_at' => ['field' => $field]]),
            ])
            ->values()
            ->all();
    }

    /**
     * @return array{0: Collection<int, int>, 1: Collection<int, int>}
     */
    private function startedAndBookedVisitIds(): array
    {
        if ($this->visitIds !== null) {
            return $this->visitIds;
        }

        $started = $this->eventsInPeriod('form_start')
            ->where('label', self::FORM)
            ->distinct()
            ->pluck('site_visit_id');

        $booked = DemoBooking::whereIn('site_visit_id', $started)->distinct()->pluck('site_visit_id');

        return $this->visitIds = [$started, $booked];
    }
}
