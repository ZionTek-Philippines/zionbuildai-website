@php
    $grid = $this->getGrid();
    $maxVisits = max([1, ...array_merge(...array_values($grid))]);
    $busiest = $this->busiestSlot($grid);
@endphp

{{-- Inline styles: the panel uses Filament's prebuilt CSS, which has no utilities for this custom layout --}}
<x-filament-widgets::widget>
    <x-filament::section
        heading="Busiest days and hours"
        :description="$busiest ? 'Visits by when they started (Philippine time). Busiest: ' . $busiest . '. Good for timing ads and follow-up calls.' : 'Visits by when they started (Philippine time).'"
    >
        <div style="overflow-x: auto;">
            <div style="display: grid; grid-template-columns: 2.5rem repeat(24, minmax(1.1rem, 1fr)); gap: 3px; min-width: 34rem; font-size: 0.6875rem;">
                <div></div>
                @foreach (range(0, 23) as $hour)
                    <div style="text-align: center; opacity: 0.6;">{{ $hour % 3 === 0 ? $this->hourLabel($hour) : '' }}</div>
                @endforeach

                @foreach ($grid as $day => $hours)
                    <div style="display: flex; align-items: center; opacity: 0.7;">{{ \App\Filament\Widgets\Analytics\BusiestTimes::DAYS[$day] }}</div>
                    @foreach ($hours as $hour => $visits)
                        <div
                            title="{{ \App\Filament\Widgets\Analytics\BusiestTimes::DAYS[$day] }} {{ $this->hourLabel($hour) }}: {{ $visits }} {{ str('visit')->plural($visits) }}"
                            style="height: 1.375rem; border-radius: 4px; background: {{ $visits > 0 ? 'color-mix(in oklab, var(--primary-500) ' . (15 + round($visits / $maxVisits * 85)) . '%, transparent)' : 'rgb(127 127 127 / 0.1)' }};"
                        ></div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
