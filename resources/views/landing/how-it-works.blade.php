@php
    $steps = [
        ['number' => '01', 'title' => 'Book a demo and project assessment', 'description' => 'We assess your current sites, ongoing contractor projects, workforce structure, and existing reporting workflow.'],
        ['number' => '02', 'title' => 'Set up your projects, team and budgets', 'description' => 'Fast setup of active job sites, worker rosters, wage rates, suppliers, and initial BOQ milestone baselines.'],
        ['number' => '03', 'title' => 'Track everything daily, from site to office', 'description' => 'Foremen log photos and attendance on phones; management and homeowners track real progress instantly on desktop.'],
    ];
@endphp

<section data-track-section="How It Works" id="how-it-works" class="w-full scroll-mt-20 bg-surface py-20">
    <div class="mx-auto max-w-7xl px-margin-sm lg:px-margin">
        <div class="mx-auto mb-16 max-w-3xl space-y-2 text-center">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-primary-fixed px-4 py-1.5 font-label-md text-label-md font-bold tracking-wider text-on-primary-fixed uppercase">
                <span class="material-symbols-outlined text-[18px]">bolt</span>
                3 Easy Steps
            </span>
            <h2 class="font-headline-lg text-headline-lg font-extrabold text-on-surface">
                Simple onboarding. Immediate visibility.
            </h2>
            <p class="font-body-lg text-body-lg text-on-surface-variant">
                No months-long complex enterprise implementation. Be live across your job sites this week.
            </p>
        </div>

        <div class="relative grid grid-cols-1 gap-8 md:grid-cols-3">
            <div class="bg-brand-gradient absolute top-[6.75rem] right-1/6 left-1/6 hidden h-1 rounded-full opacity-30 md:block"></div>

            @foreach ($steps as $step)
                <div class="relative z-10 flex flex-col items-center space-y-4 rounded-2xl bg-surface-container-lowest p-8 text-center shadow-lg ring-1 ring-primary/10 transition-transform hover:-translate-y-1">
                    <span class="font-label-sm text-label-sm font-bold tracking-widest text-primary uppercase">Step {{ $loop->iteration }}</span>
                    <div class="bg-brand-gradient flex size-20 items-center justify-center rounded-full font-display text-headline-lg font-black text-on-primary shadow-lg shadow-primary/30 ring-8 ring-primary/10">
                        {{ $step['number'] }}
                    </div>
                    <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">{{ $step['title'] }}</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant">{{ $step['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
