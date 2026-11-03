@php
    $data = $this->getVisualData();
    $layers = [
        'clicks' => 'Click hotspots',
        'sections' => 'Reading time',
        'scroll' => 'Scroll depth',
        'fields' => 'Form drop-off',
        'dim' => 'Dim page',
    ];
@endphp

{{-- Inline styles: the panel uses Filament's prebuilt CSS, which has no utilities for this custom layout --}}
<x-filament-panels::page>
    <style>
        .zbv-toolbar { display: flex; flex-wrap: wrap; align-items: center; gap: 0.75rem 1.5rem; }
        .zbv-chip { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.35rem 0.75rem; border-radius: 9999px; font-size: 0.8125rem; font-weight: 500; border: 1px solid rgb(127 127 127 / 0.25); color: inherit; text-decoration: none; cursor: pointer; background: transparent; }
        .zbv-chip[aria-current="page"], .zbv-chip[aria-pressed="true"] { background: var(--primary-500); border-color: var(--primary-500); color: #fff; }
        .zbv-legend { height: 0.5rem; width: 9rem; border-radius: 9999px; background: linear-gradient(90deg, hsl(220 95% 55%), hsl(160 95% 45%), hsl(55 95% 50%), hsl(0 95% 55%)); }
        .zbv-frame-wrap { display: flex; justify-content: center; border-radius: 1rem; overflow: hidden; background: rgb(127 127 127 / 0.08); border: 1px solid rgb(127 127 127 / 0.2); }
        .zbv-frame { width: 100%; height: max(640px, calc(100vh - 17rem)); border: 0; background: #fff; transition: width 0.3s ease; }
    </style>

    <div data-visual-analytics style="display: grid; grid-template-columns: minmax(0, 1fr); gap: 1rem;">
        <script type="application/json" data-visual-data>@json($data)</script>

        <x-filament::section>
            <div class="zbv-toolbar">
                <div style="display: flex; flex-wrap: wrap; gap: 0.375rem;">
                    @foreach (\App\Filament\Pages\SiteAnalytics::PERIODS as $key => $label)
                        <a href="{{ static::getUrl(['period' => $key]) }}" class="zbv-chip" @if ($this->period === (string) $key) aria-current="page" @endif>{{ $label }}</a>
                    @endforeach
                </div>

                <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
                    @foreach ($layers as $key => $label)
                        <label style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.8125rem; cursor: pointer;">
                            <input type="checkbox" data-visual-toggle="{{ $key }}" checked>
                            {{ $label }}
                        </label>
                    @endforeach
                </div>

                <div style="display: flex; gap: 0.375rem;">
                    <button type="button" class="zbv-chip" data-visual-device="desktop" aria-pressed="true">Desktop</button>
                    <button type="button" class="zbv-chip" data-visual-device="mobile" aria-pressed="false">Mobile</button>
                </div>

                <div style="display: flex; align-items: center; gap: 0.5rem; margin-left: auto; font-size: 0.75rem; opacity: 0.8;">
                    Less <span class="zbv-legend"></span> More
                </div>
            </div>

            <p style="margin-top: 0.75rem; font-size: 0.8125rem; opacity: 0.75;">
                {{ number_format($data['homepageVisits']) }} homepage visits in the last {{ strtolower($data['period']) }} ({{ number_format($this->getTotalVisits()) }} site visits in total).
                Glowing dots are buttons visitors clicked: bigger and redder means more clicks. Click a dot for details.
                Sections are tinted by average reading time, and dashed lines show how far down visitors scrolled.
                FAQ answers and feature tabs still open; links and forms are disabled in this preview.
            </p>
        </x-filament::section>

        <div data-visual-unmatched-wrapper hidden>
            <x-filament::section heading="Not visible on this layout" description="These were clicked, but aren't on screen at this width (for example desktop-only buttons in Mobile view, or a form already replaced by its thank-you message).">
                <ul data-visual-unmatched style="display: grid; gap: 0.25rem; font-size: 0.8125rem; list-style: disc; padding-left: 1.25rem;"></ul>
            </x-filament::section>
        </div>

        <div class="zbv-frame-wrap" wire:ignore>
            <iframe data-visual-frame class="zbv-frame" src="{{ url('/') }}" title="Homepage with analytics overlay"></iframe>
        </div>
    </div>

    @vite('resources/js/visual-analytics.js')
</x-filament-panels::page>
