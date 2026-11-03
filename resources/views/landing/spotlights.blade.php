@php
    $milestones = [
        ['status' => 'COMPLETED', 'name' => 'Groundbreaking', 'statusClass' => 'text-tertiary font-bold', 'cardClass' => 'bg-surface-container-low'],
        ['status' => 'COMPLETED', 'name' => 'Structural 2F Pour', 'statusClass' => 'text-tertiary font-bold', 'cardClass' => 'bg-surface-container-low'],
        ['status' => 'IN PROGRESS', 'name' => 'Rough Plumbing', 'statusClass' => 'text-primary font-bold', 'cardClass' => 'bg-surface-container-high'],
        ['status' => 'UPCOMING', 'name' => 'Finishing Works', 'statusClass' => 'text-on-surface-variant', 'cardClass' => 'bg-surface-container-low'],
    ];

    $costPhases = [
        ['name' => 'Phase 1: Substructure & Concrete Framing', 'spent' => '₱2,100,000', 'budget' => '₱2,300,000', 'barClass' => 'w-[91%] bg-primary'],
        ['name' => 'Phase 2: Rough MEPFS & Masonry', 'spent' => '₱1,450,000', 'budget' => '₱2,000,000', 'barClass' => 'w-[72%] bg-tertiary'],
        ['name' => 'Phase 3: Architectural & Tiles', 'spent' => '₱650,000', 'budget' => '₱2,200,000', 'barClass' => 'w-[29%] bg-secondary'],
    ];
@endphp

<section class="w-full bg-surface py-20">
    <div class="mx-auto max-w-7xl space-y-24 px-margin-sm lg:px-margin">
        {{-- Schedule --}}
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-12">
            <div class="space-y-4 lg:col-span-6">
                <span class="font-label-md text-label-md font-bold tracking-wider text-primary uppercase">Schedule Precision</span>
                <h2 class="font-headline-lg text-headline-lg font-extrabold text-on-surface">
                    Hindi sapat ang "malapit na matapos."
                </h2>
                <p class="font-body-lg text-body-lg text-on-surface-variant">
                    Gut-feel timelines lead to penalty liquidated damages and angry clients. ZionBuild charts planned milestone baselines against verified site progress so you spot bottlenecks weeks before they impact turnover.
                </p>
                <div class="space-y-3 pt-2">
                    <div class="flex items-center gap-3">
                        <span class="size-2.5 shrink-0 rounded-full bg-primary"></span>
                        <span class="font-body-md text-body-md text-on-surface">Baseline S-Curve generation calibrated for residential and commercial timelines</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="size-2.5 shrink-0 rounded-full bg-tertiary"></span>
                        <span class="font-body-md text-body-md text-on-surface">Critical path identification across rough masonry, MEPFS, and architectural finishes</span>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-surface-container-lowest p-6 shadow-md lg:col-span-6 lg:p-8">
                <div class="mb-6 flex items-center justify-between gap-3 border-b border-surface-container-high pb-4">
                    <div>
                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase">S-Curve Analysis</span>
                        <h3 class="font-title-md text-title-md font-bold text-on-surface">Two-Storey Residential Baseline vs Actual</h3>
                    </div>
                    <span class="shrink-0 rounded-full bg-tertiary-container/15 px-3 py-1 font-label-sm text-label-sm font-bold text-tertiary">+3.4% Ahead</span>
                </div>

                <div class="relative flex h-56 w-full flex-col justify-between rounded-xl bg-surface-container-low p-4">
                    <div class="flex justify-between font-label-sm text-label-sm text-on-surface-variant">
                        <span>100%</span>
                        <span>Turnover Target: Week 24</span>
                    </div>
                    <svg class="h-36 w-full" preserveAspectRatio="none" viewBox="0 0 400 120" role="img" aria-label="S-curve chart: actual progress ahead of planned baseline">
                        <line x1="0" x2="400" y1="30" y2="30" stroke="#d5e3fc" stroke-dasharray="4" stroke-width="1" />
                        <line x1="0" x2="400" y1="60" y2="60" stroke="#d5e3fc" stroke-dasharray="4" stroke-width="1" />
                        <line x1="0" x2="400" y1="90" y2="90" stroke="#d5e3fc" stroke-dasharray="4" stroke-width="1" />
                        <path d="M 0,115 Q 120,110 200,60 T 400,10" fill="none" stroke="#5b5a86" stroke-dasharray="4" stroke-width="2" />
                        <path d="M 0,115 Q 120,105 190,52" fill="none" stroke="#005338" stroke-width="3.5" />
                        <circle cx="190" cy="52" r="5" fill="#005338" />
                    </svg>
                    <div class="flex flex-wrap items-center justify-between gap-2 pt-2 font-label-sm text-label-sm text-on-surface-variant">
                        <span class="font-bold text-tertiary">Week 12 (Current: 68.4%)</span>
                        <div class="flex items-center gap-4">
                            <span class="flex items-center gap-1.5"><span class="h-0.5 w-3 bg-secondary"></span> Planned Baseline</span>
                            <span class="flex items-center gap-1.5"><span class="h-1 w-3 bg-tertiary"></span> Actual Progress</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-2 text-center sm:grid-cols-4">
                    @foreach ($milestones as $milestone)
                        <div class="rounded-lg p-2.5 {{ $milestone['cardClass'] }}">
                            <span class="font-label-sm text-label-sm {{ $milestone['statusClass'] }}">{{ $milestone['status'] }}</span>
                            <p class="mt-1 font-label-md text-label-md font-semibold text-on-surface">{{ $milestone['name'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Financial Control --}}
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-12">
            <div class="order-2 rounded-3xl bg-surface-container-lowest p-6 shadow-md lg:order-1 lg:col-span-6 lg:p-8">
                <div class="mb-6 flex items-center justify-between gap-3 border-b border-surface-container-high pb-4">
                    <div>
                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase">Financial Ledger</span>
                        <h3 class="font-title-md text-title-md font-bold text-on-surface">Site Cost Allocation vs Budget</h3>
                    </div>
                    <span class="shrink-0 font-label-md text-label-md font-bold text-on-surface">₱4.2M / ₱6.5M</span>
                </div>

                <div class="mb-6 space-y-4">
                    @foreach ($costPhases as $phase)
                        <div>
                            <div class="mb-1 flex flex-wrap justify-between gap-x-3 font-label-md text-label-md text-on-surface">
                                <span>{{ $phase['name'] }}</span>
                                <span class="font-bold">{{ $phase['spent'] }} / {{ $phase['budget'] }}</span>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-surface-container-high">
                                <div class="h-2 rounded-full {{ $phase['barClass'] }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-2">
                    <span class="font-label-sm text-label-sm font-bold text-on-surface-variant uppercase">Categorized Site Expenses Today</span>
                    <div class="flex items-center justify-between gap-3 rounded-xl bg-surface-container-low p-3">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[20px] text-primary">local_shipping</span>
                            <div>
                                <p class="font-body-sm text-body-sm font-semibold text-on-surface">50 bags Eagle Portland Cement</p>
                                <p class="font-label-sm text-label-sm text-on-surface-variant">Delivered by North Hardware • PO-890</p>
                            </div>
                        </div>
                        <span class="font-title-md text-title-md font-bold text-on-surface">₱12,500</span>
                    </div>
                    <div class="flex items-center justify-between gap-3 rounded-xl bg-surface-container-low p-3">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[20px] text-tertiary">payments</span>
                            <div>
                                <p class="font-body-sm text-body-sm font-semibold text-on-surface">Worker Vale / CA Payroll Draw</p>
                                <p class="font-label-sm text-label-sm text-on-surface-variant">Rebar fixing team (8 workers)</p>
                            </div>
                        </div>
                        <span class="font-title-md text-title-md font-bold text-on-surface">₱18,400</span>
                    </div>
                </div>
            </div>

            <div class="order-1 space-y-4 lg:order-2 lg:col-span-6">
                <span class="font-label-md text-label-md font-bold tracking-wider text-primary uppercase">Financial Assurance</span>
                <h2 class="font-headline-lg text-headline-lg font-extrabold text-on-surface">
                    Bawat gastos, may malinaw na record.
                </h2>
                <p class="font-body-lg text-body-lg text-on-surface-variant">
                    Stop losing profit margins to untracked petty cash vouchers and forgotten hardware delivery notes. ZionBuild locks in every invoice, worker cash advance, and supplier receipt with photos and project ledger tags.
                </p>
                <div class="space-y-3 pt-2">
                    <div class="flex items-center gap-3">
                        <span class="size-2.5 shrink-0 rounded-full bg-primary"></span>
                        <span class="font-body-md text-body-md text-on-surface">Automatic worker vale reconciliation during Friday payroll</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="size-2.5 shrink-0 rounded-full bg-tertiary"></span>
                        <span class="font-body-md text-body-md text-on-surface">Real-time alerts whenever a trade package exceeds 85% of projected BOQ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
