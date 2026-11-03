@php
    $rows = $this->getRows();
    $maxValue = max([1, ...array_column($rows, 'value')]);
@endphp

{{-- Inline styles: the panel uses Filament's prebuilt CSS, which has no utilities for this custom layout --}}
<x-filament-widgets::widget>
    <style>
        .zb-ranked-row { display: block; margin: 0 -0.5rem; padding: 0.625rem 0.5rem; border-radius: 0.5rem; color: inherit; text-decoration: none; }
        a.zb-ranked-row:hover { background: rgb(127 127 127 / 0.08); }
        a.zb-ranked-row:hover .zb-ranked-open { opacity: 1; }
    </style>

    <x-filament::section :heading="$this->getHeading()" :description="$this->getDescription()">
        @forelse ($rows as $row)
            <div style="{{ $loop->last ? '' : 'border-bottom: 1px solid rgb(127 127 127 / 0.12);' }}">
                @if (! empty($row['url']))
                    <a href="{{ $row['url'] }}" class="zb-ranked-row" title="See the records behind this number">
                @else
                    <div class="zb-ranked-row">
                @endif
                    <div style="display: flex; align-items: baseline; justify-content: space-between; gap: 1rem;">
                        <div style="min-width: 0;">
                            <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.875rem; font-weight: 500;" title="{{ $row['label'] }}">{{ $row['label'] }}</div>
                            @if (! empty($row['sublabel']))
                                <div style="font-size: 0.75rem; opacity: 0.6;">{{ $row['sublabel'] }}</div>
                            @endif
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.25rem; white-space: nowrap; font-size: 0.875rem; font-weight: 600; font-variant-numeric: tabular-nums;">
                            {{ $row['display'] }}
                            @if (! empty($row['url']))
                                <span class="zb-ranked-open" style="opacity: 0.35; font-size: 1rem; line-height: 1;" aria-hidden="true">&rsaquo;</span>
                            @endif
                        </div>
                    </div>
                    <div style="margin-top: 0.375rem; height: 0.375rem; overflow: hidden; border-radius: 9999px; background: rgb(127 127 127 / 0.12);">
                        <div style="height: 100%; width: {{ round($row['value'] / $maxValue * 100, 1) }}%; border-radius: 9999px; background: var(--primary-500);"></div>
                    </div>
                @if (! empty($row['url']))
                    </a>
                @else
                    </div>
                @endif
            </div>
        @empty
            <p style="padding: 1.5rem 0; text-align: center; font-size: 0.875rem; opacity: 0.6;">No data for this period yet.</p>
        @endforelse
    </x-filament::section>
</x-filament-widgets::widget>
