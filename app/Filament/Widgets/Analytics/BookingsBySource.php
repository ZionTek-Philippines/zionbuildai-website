<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\DemoBooking;

class BookingsBySource extends RankedListWidget
{
    public function getHeading(): string
    {
        return 'Bookings by source';
    }

    public function getDescription(): ?string
    {
        return 'Which channels bring actual demo bookings (source of the visit they booked in), and how well each converts.';
    }

    public function getRows(): array
    {
        $visitsBySource = $this->visitsBySource();

        return DemoBooking::query()
            ->with('visit')
            ->where('created_at', '>=', $this->periodStart())
            ->get()
            ->groupBy(fn (DemoBooking $booking): string => $booking->visit?->source() ?? 'Not tracked')
            ->map(function ($bookings, string $source) use ($visitsBySource): array {
                $count = $bookings->count();

                return [
                    'label' => $source,
                    'sublabel' => $source === 'Not tracked'
                        ? 'Visitor had tracking blocked or turned off'
                        : $this->percentage($count, $visitsBySource[$source] ?? 0).' of '.number_format($visitsBySource[$source] ?? 0).' visits converted',
                    'value' => $count,
                    'display' => number_format($count),
                    'url' => $this->bookingsUrl(['source' => ['value' => $source]]),
                ];
            })
            ->sortByDesc('value')
            ->values()
            ->all();
    }
}
