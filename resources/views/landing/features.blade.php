@php
    $features = [
        ['key' => 'attendance', 'icon' => 'how_to_reg', 'title' => 'Attendance & Site Progress Update', 'description' => 'Daily attendance and site progress, logged from the field.', 'quote' => 'Alam mo kung sino ang pumasok, kailan pumasok, at saang project.', 'previewTitle' => 'Attendance & Site Progress Update', 'video' => '01.02._Attendance_and_Site Progress_Update.mov'],
        ['key' => 'monitoring', 'icon' => 'photo_camera', 'title' => 'Project Monitoring & Photo Analysis', 'description' => 'Track site progress through daily photos, with AI photo analysis.', 'quote' => 'Actual construction progress photos, kita sa phone mo.', 'previewTitle' => 'Project Monitoring & Photo Analysis', 'video' => '03.04._PROJECT MONITORING_and_AI_PHOTO_ANALYSIS.mov'],
        ['key' => 'budget', 'icon' => 'payments', 'title' => 'Budget, Expenses & Payroll', 'description' => 'Budget vs actual, expenses, wages, vale and cash advances.', 'quote' => 'Huwag hintaying maubos ang budget bago malaman na may problema.', 'previewTitle' => 'Budget, Expenses & Payroll', 'video' => '05.06._BUDGET_EXPENSES_PAYROLL.mov'],
        ['key' => 'client', 'icon' => 'supervised_user_circle', 'title' => 'Homeowner App', 'description' => 'Keep homeowners informed automatically.', 'quote' => 'Updated ka sa bahay mo kahit nasa office ka.', 'previewTitle' => 'Homeowner App', 'video' => '10.HOMEOWNER_APP.mov'],
        ['key' => 'command', 'icon' => 'dashboard', 'title' => 'Command Center', 'description' => 'See every project at a glance.', 'quote' => 'Alam mo ang nangyayari sa bawat project — kahit wala ka sa site.', 'previewTitle' => 'Command Center View', 'image' => 'hero-command-center.jpg', 'imageAlt' => 'ZionBuild Command Center: contract price, project cost, earnings, net cash, client payments, payroll and expenses for one project', 'demoRoute' => 'demo.command-center'],
        ['key' => 'milestones', 'icon' => 'trending_up', 'title' => 'Milestones & S-Curve', 'description' => 'Planned vs actual progress, clearly charted.', 'quote' => 'Alamin kung ahead, on schedule, o delayed ang project.', 'previewTitle' => 'Planned S-Curve vs Site Milestone Accomplishment', 'image' => 'feature-s-curve.jpg', 'imageAlt' => 'ZionBuild S-Curve: planned vs actual cumulative progress, 42.0% actual against 40.4% planned at week 18', 'demoRoute' => 'demo.ai-project-monitoring'],
        ['key' => 'inventory', 'icon' => 'inventory', 'title' => 'Inventory & Equipment', 'description' => 'Track materials and equipment across sites.', 'quote' => 'Alam mo kung anong materials ang dumating, nagamit, at natitira.', 'previewTitle' => 'Site Materials & Tool Movements'],
        ['key' => 'boq', 'icon' => 'receipt_long', 'title' => 'BOQ & Procurement', 'description' => 'From estimates to purchase orders.', 'quote' => 'Mas organisadong planning at purchasing.', 'previewTitle' => 'Bill of Quantities (BOQ) & Purchase Orders'],
    ];
@endphp

<section data-track-section="Features" id="features" class="w-full scroll-mt-20 bg-surface py-20">
    <div class="mx-auto max-w-7xl px-margin-sm lg:px-margin">
        <div class="mb-12 max-w-3xl space-y-2">
            <span class="font-label-md text-label-md font-bold tracking-wider text-primary uppercase">Features</span>
            <h2 class="font-headline-lg text-headline-lg font-extrabold text-on-surface">
                Everything your construction business runs on.
            </h2>
            <p class="font-body-lg text-body-lg text-on-surface-variant">
                Built precisely for the tempo of Philippine commercial and residential building operations.
            </p>
        </div>

        <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-12" data-feature-showcase>
            <div class="space-y-2.5 lg:col-span-5" role="tablist" aria-label="Features">
                @foreach ($features as $feature)
                    <button
                        type="button"
                        role="tab"
                        id="feature-tab-{{ $feature['key'] }}"
                        aria-controls="feature-panel-{{ $feature['key'] }}"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                        data-feature-tab="{{ $feature['key'] }}"
                        data-track="{{ $feature['title'] }}"
                        class="group block w-full cursor-pointer rounded-xl bg-surface-container-lowest p-4 text-left shadow-xs ring-1 ring-on-surface/5 transition-all hover:-translate-y-0.5 hover:shadow-md hover:ring-primary/30 aria-selected:bg-inverse-surface aria-selected:shadow-lg aria-selected:ring-inverse-surface"
                    >
                        <span class="flex items-start gap-3">
                            <span class="material-symbols-outlined mt-0.5 text-[22px] text-primary group-aria-selected:text-primary-fixed">{{ $feature['icon'] }}</span>
                            <span class="min-w-0 flex-1">
                                <span class="block font-title-md text-title-md font-bold text-on-surface group-aria-selected:text-inverse-on-surface">{{ $feature['title'] }}</span>
                                <span class="block font-body-sm text-body-sm text-on-surface-variant group-aria-selected:text-surface-container-highest">{{ $feature['description'] }}</span>
                                <span class="mt-1 block font-label-sm text-label-sm font-medium text-primary group-aria-selected:text-primary-fixed">"{{ $feature['quote'] }}"</span>
                            </span>
                            <span class="material-symbols-outlined mt-0.5 shrink-0 text-[22px] text-on-surface-variant transition-transform group-hover:translate-x-0.5 group-hover:text-primary group-aria-selected:text-inverse-on-surface">chevron_right</span>
                        </span>
                    </button>
                @endforeach
            </div>

            <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-lg lg:sticky lg:top-28 lg:col-span-7">
                <div class="mb-6 flex items-center justify-between gap-3 border-b border-surface-container-high pb-4">
                    <div class="flex items-center gap-2">
                        <span class="size-2.5 shrink-0 rounded-full bg-tertiary"></span>
                        <span class="font-title-md text-title-md font-bold text-on-surface" data-feature-title>{{ $features[0]['previewTitle'] }}</span>
                    </div>
                    <span class="shrink-0 rounded-full bg-surface-container px-3 py-1 font-label-sm text-label-sm text-on-surface-variant">Interactive Sandbox</span>
                </div>

                @foreach ($features as $feature)
                    <div
                        id="feature-panel-{{ $feature['key'] }}"
                        role="tabpanel"
                        aria-labelledby="feature-tab-{{ $feature['key'] }}"
                        data-feature-panel="{{ $feature['key'] }}"
                        data-preview-title="{{ $feature['previewTitle'] }}"
                        @class(['space-y-6', 'hidden' => ! $loop->first])
                    >
                        @include(match (true) {
                            isset($feature['video']) => 'landing.feature-previews.video',
                            isset($feature['image']) => 'landing.feature-previews.screenshot',
                            default => 'landing.feature-previews.'.$feature['key'],
                        })
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
