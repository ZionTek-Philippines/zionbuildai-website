@php
    $aiCapabilities = [
        ['icon' => 'document_scanner', 'title' => 'AI Photo Analysis', 'description' => 'AI reviews the photos on a work log or progress update and suggests which milestone items they match, with a confidence level and a suggested progress range.', 'tag' => 'You accept every match'],
        ['icon' => 'fact_check', 'title' => 'AI Progress Validation', 'description' => 'Suggests a progress % for each milestone item from photos, daily reports, attendance and inventory.', 'tag' => 'Approve, modify or reject'],
        ['icon' => 'lightbulb', 'title' => 'AI Recommendations', 'description' => 'Flags schedule and quality issues with severity, evidence and a recommended action.', 'tag' => 'Acknowledge or dismiss'],
    ];
@endphp

<section data-track-section="Practical AI" class="relative w-full overflow-hidden bg-inverse-surface py-20 text-inverse-on-surface">
    <div class="pointer-events-none absolute right-0 -bottom-24 size-96 rounded-full bg-primary/20 blur-3xl"></div>

    <div class="relative z-10 mx-auto max-w-7xl px-margin-sm lg:px-margin">
        <div class="mx-auto mb-14 max-w-3xl space-y-2 text-center">
            <span class="rounded-full bg-primary-container px-3 py-1 font-label-md text-label-md font-bold tracking-widest text-on-primary-container uppercase">
                ZIONBUILD AI
            </span>
            <h2 class="mt-3 font-headline-lg text-headline-lg font-extrabold text-inverse-on-surface">
                Practical AI for better construction decisions.
            </h2>
            <p class="font-body-lg text-body-lg text-surface-container-highest">
                AI that helps you understand your construction data, not hype. A person always makes the final call; AI never changes official progress on its own.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            @foreach ($aiCapabilities as $capability)
                <div class="flex flex-col justify-between space-y-4 rounded-2xl bg-on-surface/50 p-6 backdrop-blur-md">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-3">
                            <span class="material-symbols-outlined text-[26px] text-primary-fixed">{{ $capability['icon'] }}</span>
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-400/15 px-2.5 py-0.5 font-label-sm text-label-sm font-bold text-emerald-300 ring-1 ring-emerald-400/30"><span class="size-1.5 rounded-full bg-emerald-400"></span>Available on selected projects</span>
                        </div>
                        <h3 class="font-title-md text-title-md font-bold text-inverse-on-surface">{{ $capability['title'] }}</h3>
                        <p class="font-body-sm text-body-sm text-surface-container-highest">{{ $capability['description'] }}</p>
                    </div>
                    <div class="pt-2 font-label-sm text-label-sm font-semibold text-primary-fixed">{{ $capability['tag'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>
