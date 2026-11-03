@php
    $contractorBenefits = [
        ['title' => 'Multi-project overview & site logs:', 'description' => 'Monitor five, ten, or twenty concurrent job sites without driving through Manila traffic all day.'],
        ['title' => 'Automated payroll & vale deductions:', 'description' => 'Deduct cash advances (vale) automatically from weekly worker envelopes.'],
        ['title' => 'Real-time budget vs actual expenditure tracking:', 'description' => 'Catch cost overruns in structural rebar or concrete before your billings fall behind.'],
        ['title' => 'Exportable Accomplishment Reports:', 'description' => 'One-click formal PDF reports for bank drawdowns and client progress billings.'],
    ];

    $homeownerBenefits = [
        ['title' => 'Time-stamped daily construction progress photos:', 'description' => 'View daily photos and structural progress straight from your smartphone.'],
        ['title' => 'Milestone timeline & completion dates:', 'description' => 'Know exact target turnover schedules without asking repeatedly in chat.'],
        ['title' => 'Verified accomplishment reports:', 'description' => 'Verify completed roof trusses or tile laying before approving the next progress billing.'],
        ['title' => 'Direct site engineer accountability:', 'description' => 'Receive verified updates directly from certified on-site structural engineers.'],
    ];
@endphp

<section id="homeowners" class="w-full scroll-mt-20 bg-surface-container-low py-20">
    <div class="mx-auto max-w-7xl px-margin-sm lg:px-margin">
        <div class="mx-auto mb-14 max-w-3xl space-y-2 text-center">
            <span class="font-label-md text-label-md font-bold tracking-wider text-primary uppercase">Dual Advantage</span>
            <h2 class="font-headline-lg text-headline-lg font-extrabold text-on-surface">
                Designed for collaboration between builders and property owners.
            </h2>
            <p class="font-body-lg text-body-lg text-on-surface-variant">
                Transparency eliminates conflict. Professionalize contractor operations while giving clients undeniable peace of mind.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            {{-- For Contractors --}}
            <div class="flex flex-col justify-between space-y-8 rounded-3xl bg-surface-container-lowest p-8 shadow-xs lg:p-10">
                <div class="space-y-6">
                    <div class="inline-flex items-center gap-2 rounded-full bg-primary-fixed px-3 py-1 font-label-md text-label-md font-bold text-on-primary-fixed">
                        <span class="material-symbols-outlined text-[16px]">engineering</span> FOR CONTRACTORS
                    </div>
                    <h3 class="font-headline-md text-headline-md font-extrabold text-on-surface">
                        Control projects, expenses, workforce, materials and profitability.
                    </h3>
                    <p class="font-body-lg text-body-lg text-on-surface-variant">
                        Everything the project manager and general contractor need to protect margins and avoid site delays.
                    </p>
                    <ul class="space-y-4 pt-2 font-body-md text-body-md text-on-surface">
                        @foreach ($contractorBenefits as $benefit)
                            <li class="flex items-start gap-3">
                                <span class="material-symbols-outlined mt-0.5 shrink-0 text-[20px] text-primary">check_circle</span>
                                <span><strong>{{ $benefit['title'] }}</strong> {{ $benefit['description'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="space-y-3 rounded-2xl bg-surface-container-low p-5">
                    <div class="flex items-center justify-between gap-2 font-label-sm text-label-sm text-secondary">
                        <span>CONTRACTOR OVERVIEW • CONCURRENT PROJECTS</span>
                        <span class="font-bold text-on-surface">ACTIVE: 4</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-surface-container-lowest p-3">
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Weekly Payroll Total</span>
                            <p class="font-headline-sm text-headline-sm font-bold text-on-surface">₱184,500</p>
                            <span class="font-label-sm text-label-sm text-tertiary">₱32,000 Vale Auto-Deducted</span>
                        </div>
                        <div class="rounded-xl bg-surface-container-lowest p-3">
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Net Margin Forecast</span>
                            <p class="font-headline-sm text-headline-sm font-bold text-primary">19.4%</p>
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Within target range</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- For Homeowners --}}
            <div class="flex flex-col justify-between space-y-8 rounded-3xl bg-inverse-surface p-8 text-inverse-on-surface shadow-xl lg:p-10">
                <div class="space-y-6">
                    <div class="inline-flex items-center gap-2 rounded-full bg-primary px-3 py-1 font-label-md text-label-md font-bold text-on-primary">
                        <span class="material-symbols-outlined text-[16px]">cottage</span> FOR HOMEOWNERS &amp; INVESTORS
                    </div>
                    <h3 class="font-headline-md text-headline-md font-extrabold text-inverse-on-surface">
                        Finally, makita mo ang progress ng bahay mo.
                    </h3>
                    <p class="font-body-lg text-body-lg text-surface-container-highest">
                        Walang kaba. Walang hulaan. See verified site accomplishments before releasing milestone progress payments.
                    </p>
                    <ul class="space-y-4 pt-2 font-body-md text-body-md text-inverse-on-surface">
                        @foreach ($homeownerBenefits as $benefit)
                            <li class="flex items-start gap-3">
                                <span class="material-symbols-outlined mt-0.5 shrink-0 text-[20px] text-tertiary-fixed">verified</span>
                                <span><strong>{{ $benefit['title'] }}</strong> {{ $benefit['description'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="space-y-3 rounded-2xl bg-on-surface/40 p-5 backdrop-blur-md">
                    <div class="flex items-center justify-between font-label-sm text-label-sm text-surface-container-highest">
                        <span>MY HOMEOWNER PORTAL</span>
                        <span class="font-bold text-tertiary-fixed">MILESTONE 4 / 6</span>
                    </div>
                    <div class="space-y-2 rounded-xl bg-on-surface/60 p-3.5">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span class="font-title-md text-title-md font-bold text-inverse-on-surface">Roofing &amp; Waterproofing</span>
                            <span class="rounded bg-tertiary-container px-2 py-0.5 font-label-sm text-label-sm font-bold text-on-tertiary-container">Ready for Inspection</span>
                        </div>
                        <p class="font-body-sm text-body-sm text-surface-container-highest">Trusses aligned, insulation membrane installed. 12 fresh inspection photos uploaded.</p>
                        <div class="flex items-center justify-between pt-1">
                            <span class="font-label-sm text-label-sm text-surface-container-highest">Drawdown Amount: ₱450,000</span>
                            <span class="font-label-sm text-label-sm text-primary-fixed underline">Review &amp; Approve</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12 text-center">
            <p class="font-title-md text-title-md font-medium text-secondary">
                ZionBuild builds <span class="font-bold text-on-surface">trust and transparency</span> between contractors and their clients.
            </p>
        </div>
    </div>
</section>
