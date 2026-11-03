@php
    $peso = fn (float $amount): string => '₱'.number_format($amount);

    // Collected ₱4,080,000 = the Command Center's Total Inflow. The pending check is not counted until it clears.
    $payments = [
        ['ref' => 'CP-0000003', 'date' => 'Oct 31, 2026', 'description' => 'Progress Billing #2', 'type' => 'Check', 'icon' => 'request_quote', 'reference' => 'CHK 0045821', 'amount' => 1_020_000, 'received' => false],
        ['ref' => 'CP-0000002', 'date' => 'Aug 28, 2026', 'description' => 'Progress Billing #1', 'type' => 'Bank Transfer', 'icon' => 'sync_alt', 'reference' => 'BT-82710', 'amount' => 1_020_000, 'received' => true],
        ['ref' => 'CP-0000001', 'date' => 'Jun 30, 2026', 'description' => '30% Downpayment', 'type' => 'Check', 'icon' => 'request_quote', 'reference' => 'CHK 0045102', 'amount' => 3_060_000, 'received' => true],
    ];
    $contractPrice = 10_200_000;
    $collected = collect($payments)->where('received', true)->sum('amount');
    $pending = collect($payments)->where('received', false)->sum('amount');
    $accomplished = 4_284_000; // from the Accomplishment Report (42%)

    // Only the updates marked "Show to client" in Field Reports.
    $sharedUpdates = [
        ['date' => 'Oct 31', 'note' => 'Second-floor slab rebar tying, about 60% done.', 'photo' => 'fr-01'],
        ['date' => 'Oct 30', 'note' => 'Slab formworks installed on grids A–C.', 'photo' => 'fr-04'],
        ['date' => 'Oct 28', 'note' => 'Ground-floor CHB walls completed.', 'photo' => 'fr-07'],
    ];

    $tourSteps = [
        [
            'target' => null,
            'en' => ['title' => 'Client Billing: get paid, keep them informed', 'body' => 'Every payment from your client in one place, and a portal where your homeowner sees progress without calling you.'],
            'tl' => ['title' => 'Client Billing: mabayaran, at updated ang client', 'body' => 'Lahat ng bayad ng client mo sa iisang lugar, at portal kung saan nakikita ng homeowner ang progress nang hindi ka na tinatawagan.'],
        ],
        [
            'target' => 'billing-kpis', 'tab' => 'payments',
            'en' => ['title' => 'Collections at a glance', 'body' => 'Contract price, what has been collected, the balance, and payments still clearing.', 'example' => '₱4,080,000 collected (40%), with a ₱1,020,000 check still clearing.'],
            'tl' => ['title' => 'Koleksyon, isang tingin', 'body' => 'Contract price, magkano na ang nakolekta, ang balance, at mga bayad na nagki-clear pa.', 'example' => '₱4,080,000 na ang nakolekta (40%), at may ₱1,020,000 na tseke pang nagki-clear.'],
        ],
        [
            'target' => 'collection-bar', 'tab' => 'payments',
            'en' => ['title' => 'Work done vs money in', 'body' => 'Compare what you have built with what you have been paid, so you know when to bill next.', 'example' => '₱4,284,000 of work done vs ₱4,080,000 collected: you are ₱204,000 ahead, a good time to bill.'],
            'tl' => ['title' => 'Nagawang trabaho vs perang pumasok', 'body' => 'Ikumpara ang natapos mo sa naibayad sa iyo, para alam mo kung kailan susunod na magbi-bill.', 'example' => '₱4,284,000 na ang natapos vs ₱4,080,000 na nakolekta: lamang ka ng ₱204,000, magandang oras para mag-bill.'],
        ],
        [
            'target' => 'payments-table', 'tab' => 'payments',
            'en' => ['title' => 'Every payment, recorded', 'body' => 'How the client paid, the reference number, and which account it went into.', 'example' => 'CP-0000002: ₱1,020,000 by bank transfer on Aug 28, into Main Bank.'],
            'tl' => ['title' => 'Bawat bayad, may record', 'body' => 'Paano nagbayad ang client, ang reference number, at saang account pumasok.', 'example' => 'CP-0000002: ₱1,020,000 via bank transfer noong Aug 28, pumasok sa Main Bank.'],
        ],
        [
            'target' => 'portal-preview', 'tab' => 'portal',
            'en' => ['title' => 'What your client sees', 'body' => 'Your homeowner gets their own view: progress, the next milestone, the photos you shared and what they have paid.', 'example' => 'The Ong family sees 42% done, the next milestone on Nov 7, and only the updates you shared.'],
            'tl' => ['title' => 'Ang nakikita ng client mo', 'body' => 'May sariling view ang homeowner mo: progress, susunod na milestone, mga photos na shine-share mo at magkano na ang naibayad nila.', 'example' => 'Nakikita ng pamilyang Ong na 42% na tapos, ang susunod na milestone sa Nov 7, at mga update lang na shine-share mo.'],
        ],
        [
            'target' => 'portal-private', 'tab' => 'portal',
            'en' => ['title' => 'Your numbers stay private', 'body' => 'Costs, budget, expenses and payroll are never shown to the client.', 'example' => 'They see ₱4,080,000 paid, never your ₱7,140,000 budget or your margin.'],
            'tl' => ['title' => 'Pribado ang numbers mo', 'body' => 'Hindi kailanman ipinapakita sa client ang gastos, budget, expenses at payroll.', 'example' => 'Nakikita nila ang ₱4,080,000 na naibayad, hindi ang ₱7,140,000 mong budget o ang kita mo.'],
        ],
        [
            'target' => null, 'final' => true,
            'en' => ['title' => "That's the ZionBuild demo", 'body' => 'Command Center, field reports, AI photo analysis, finances, payroll and client billing, in one system. Ready to see it with your own projects?'],
            'tl' => ['title' => 'Yan ang ZionBuild demo', 'body' => 'Command Center, field reports, AI photo analysis, finances, payroll at client billing, sa iisang system. Handa ka na bang makita ito gamit ang sarili mong projects?'],
        ],
    ];

    $tabs = [
        ['key' => 'payments', 'label' => 'Client Payments', 'icon' => 'receipt_long'],
        ['key' => 'portal', 'label' => 'Client Portal', 'icon' => 'smartphone'],
    ];
@endphp

<x-demo.layout title="Client Billing" active="client-billing" active-icon="receipt">
    <div class="space-y-6">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="font-demo text-[28px] font-extrabold tracking-tight text-demo-navy">Client Billing</h1>
                <p class="text-sm text-slate-500">Every payment from your client, and what they see in their portal.</p>
            </div>
            <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-600">
                <span class="material-symbols-outlined text-[15px]">calendar_today</span>
                As of Sat, Oct 31, 2026
            </span>
        </div>

        <div role="tablist" class="flex gap-1 self-start overflow-x-auto rounded-full border border-slate-200/60 bg-white/80 p-1.5 shadow-sm [scrollbar-width:none] sm:inline-flex">
            @foreach ($tabs as $tab)
                <button type="button" role="tab" data-demo-tab="{{ $tab['key'] }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" @disabled($isDemoLocked && ! $loop->first) class="disabled:cursor-not-allowed disabled:opacity-50 inline-flex shrink-0 items-center gap-1.5 rounded-full px-4 py-2 text-xs font-medium text-slate-600 transition hover:bg-slate-100 aria-selected:bg-demo-navy aria-selected:font-bold aria-selected:text-white">
                    <span class="material-symbols-outlined text-[16px]">{{ $tab['icon'] }}</span>
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>

        {{-- ==================== Client Payments ==================== --}}
        <div data-demo-panel="payments" class="space-y-6">
            <section data-tour="billing-kpis" class="relative grid grid-cols-2 gap-3 lg:grid-cols-4 lg:gap-4">
                <div class="rounded-2xl border border-slate-200/60 bg-white p-4 shadow-sm sm:p-5">
                    <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase sm:text-[11px]">Contract price</span>
                    <div class="mt-2 font-demo text-xl font-extrabold text-demo-navy sm:text-2xl">{{ $peso($contractPrice) }}</div>
                </div>
                <div class="rounded-2xl border border-slate-200/60 bg-white p-4 shadow-sm sm:p-5">
                    <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase sm:text-[11px]">Collected</span>
                    <div class="mt-2 font-demo text-xl font-extrabold text-demo-green sm:text-2xl">{{ $peso($collected) }}</div>
                    <p class="mt-1 text-xs text-slate-500">40% of contract</p>
                </div>
                <div class="rounded-2xl border border-slate-200/60 bg-white p-4 shadow-sm sm:p-5">
                    <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase sm:text-[11px]">Balance</span>
                    <div class="mt-2 font-demo text-xl font-extrabold text-demo-navy sm:text-2xl">{{ $peso($contractPrice - $collected) }}</div>
                    <p class="mt-1 text-xs text-slate-500">60% still to collect</p>
                </div>
                <div class="relative rounded-2xl border border-slate-200/60 bg-white p-4 shadow-sm sm:p-5">
                    <x-demo.info class="absolute top-3 right-3" />
                    <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase sm:text-[11px]">Pending</span>
                    <div class="mt-2 font-demo text-xl font-extrabold text-amber-600 sm:text-2xl">{{ $peso($pending) }}</div>
                    <p class="mt-1 text-xs text-amber-700">1 check clearing</p>
                </div>
            </section>

            @if ($isDemoLocked)
                @include('demo.partials.unlock-form')
            @else

            <section data-tour="collection-bar" class="relative rounded-2xl border border-slate-200/60 bg-white p-5 shadow-sm sm:p-6">
                <x-demo.info class="absolute top-5 right-5" />
                <h2 class="pr-8 font-demo text-lg font-bold text-demo-navy">Work Done vs Collected</h2>
                <p class="text-xs text-slate-500">Accomplishment Report value compared with client payments</p>

                <div class="relative mt-12">
                    <div class="absolute -top-9 left-[42%] flex -translate-x-1/2 flex-col items-center">
                        <span class="rounded-md bg-demo-navy px-2 py-1 text-[11px] font-bold whitespace-nowrap text-white">Work done {{ $peso($accomplished) }} (42%)</span>
                        <span class="h-3 w-0.5 bg-demo-navy"></span>
                    </div>
                    <div class="flex h-5 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full w-[40%] bg-demo-green"></div>
                        <div class="h-full w-[10%] bg-amber-400"></div>
                    </div>
                </div>
                <div class="mt-3 flex flex-wrap gap-x-5 gap-y-1 text-xs text-slate-600">
                    <span class="flex items-center gap-1.5"><span class="size-2.5 rounded-full bg-demo-green"></span>Collected {{ $peso($collected) }}</span>
                    <span class="flex items-center gap-1.5"><span class="size-2.5 rounded-full bg-amber-400"></span>Pending {{ $peso($pending) }}</span>
                    <span class="flex items-center gap-1.5"><span class="size-2.5 rounded-full bg-slate-200"></span>Not yet collected {{ $peso($contractPrice - $collected - $pending) }}</span>
                </div>

                <div class="mt-5 flex flex-col gap-3 rounded-xl bg-blue-50/70 p-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="flex items-start gap-2.5 text-sm text-slate-700">
                        <span class="material-symbols-outlined text-[20px] text-demo-blue">trending_up</span>
                        <span><span class="font-bold text-demo-navy">Work done is {{ $peso($accomplished - $collected) }} ahead of collections.</span> A good time to send the next progress billing.</span>
                    </p>
                    <a href="{{ route('demo.ai-project-monitoring') }}" class="inline-flex shrink-0 items-center gap-1 self-start rounded-lg bg-white px-3.5 py-1.5 text-xs font-semibold text-demo-navy shadow-sm hover:bg-slate-50">
                        See the Accomplishment Report
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </a>
                </div>
            </section>

            <section data-tour="payments-table" class="relative overflow-hidden rounded-2xl border border-slate-200/60 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 p-5 sm:p-6">
                    <div class="flex items-center gap-3">
                        <span class="flex size-10 items-center justify-center rounded-xl bg-violet-100 font-demo text-sm font-extrabold text-demo-violet">OF</span>
                        <div>
                            <h2 class="font-demo text-base font-bold text-demo-navy">Ong family</h2>
                            <p class="text-xs text-slate-500">Client since Jun 2026 · Ong Residential</p>
                        </div>
                    </div>
                    <button type="button" data-demo-toast-message="In the real app, Record Payment logs the amount, how they paid, the reference and which account received it." data-demo-toast-message-tl="Sa totoong app, nire-record ng Record Payment ang halaga, paraan ng bayad, reference at kung saang account pumasok." class="inline-flex items-center gap-1.5 rounded-xl bg-demo-blue px-3.5 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-blue-700">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        Record Payment
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[820px] text-left text-xs">
                        <thead>
                            <tr class="border-y border-slate-100 bg-slate-50/70 text-[10px] font-semibold tracking-wider text-slate-500 uppercase">
                                <th class="px-5 py-3">Payment No.</th>
                                <th class="px-3 py-3">Payment date</th>
                                <th class="px-3 py-3">Description</th>
                                <th class="px-3 py-3">Type</th>
                                <th class="px-3 py-3">Reference</th>
                                <th class="px-3 py-3">Received into</th>
                                <th class="px-3 py-3 text-right">Amount</th>
                                <th class="px-5 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($payments as $payment)
                                <tr>
                                    <td class="px-5 py-3 font-semibold text-demo-navy">{{ $payment['ref'] }}</td>
                                    <td class="px-3 py-3 whitespace-nowrap text-slate-500">{{ $payment['date'] }}</td>
                                    <td class="px-3 py-3 font-semibold text-slate-800">{{ $payment['description'] }}</td>
                                    <td class="px-3 py-3"><span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-[11px] font-semibold whitespace-nowrap text-slate-700"><span class="material-symbols-outlined text-[14px]">{{ $payment['icon'] }}</span>{{ $payment['type'] }}</span></td>
                                    <td class="px-3 py-3 font-mono whitespace-nowrap text-slate-500">{{ $payment['reference'] }}</td>
                                    <td class="px-3 py-3 whitespace-nowrap text-slate-600">Main Bank</td>
                                    <td class="px-3 py-3 text-right font-demo text-sm font-bold text-demo-navy">{{ $peso($payment['amount']) }}</td>
                                    <td class="px-5 py-3 text-center">
                                        @if ($payment['received'])
                                            <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-semibold text-demo-green">Received</span>
                                        @else
                                            <span class="rounded-full bg-amber-50 px-2.5 py-0.5 text-[11px] font-semibold text-amber-800">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="flex flex-wrap gap-x-4 gap-y-1 border-t border-slate-100 bg-slate-50/50 px-5 py-3 text-xs font-semibold">
                    <span class="text-demo-green">Received {{ $peso($collected) }}</span>
                    <span class="text-amber-700">Pending {{ $peso($pending) }}</span>
                </p>
            </section>
            @endif
        </div>

        @unless ($isDemoLocked)
        {{-- ==================== Client Portal ==================== --}}
        <div data-demo-panel="portal" class="hidden">
            <div class="grid items-start gap-6 lg:grid-cols-12">
                <div class="space-y-4 lg:col-span-5">
                    <section class="rounded-2xl border border-slate-200/60 bg-white p-6 shadow-sm">
                        <h2 class="font-demo text-lg font-bold text-demo-navy">What your client sees</h2>
                        <p class="mb-5 text-xs text-slate-500">Their own view of the project, with only what you choose to share</p>
                        <ul class="space-y-4">
                            @foreach ([
                                ['flag', 'bg-violet-50 text-demo-violet', 'Progress & milestones', 'Overall completion and what is next.'],
                                ['photo_camera', 'bg-blue-50 text-demo-blue', 'Photos you shared', 'Only updates you marked "Show to client".'],
                                ['description', 'bg-amber-50 text-amber-700', 'Accomplishment reports', 'What has been done, in a report they can read.'],
                                ['account_balance_wallet', 'bg-emerald-50 text-demo-green', 'Payments', 'What they have paid and what is left.'],
                            ] as [$icon, $tint, $title, $body])
                                <li class="flex items-start gap-3.5">
                                    <span class="flex size-10 shrink-0 items-center justify-center rounded-xl {{ $tint }}"><span class="material-symbols-outlined text-[20px]">{{ $icon }}</span></span>
                                    <span><span class="block text-sm font-bold text-demo-navy">{{ $title }}</span><span class="text-xs text-slate-500">{{ $body }}</span></span>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                    <p data-tour="portal-private" class="relative flex items-start gap-2.5 rounded-2xl border border-slate-200/60 bg-white p-4 text-sm text-slate-700 shadow-sm">
                        <span class="material-symbols-outlined text-[20px] text-demo-violet">lock</span>
                        Your costs, budget, expenses and payroll are never shown to the client.
                    </p>
                </div>

                <div data-tour="portal-preview" class="relative flex justify-center lg:col-span-7">
                    <div class="w-full max-w-[360px] rounded-[44px] bg-slate-900 p-3 shadow-2xl">
                        <div class="overflow-hidden rounded-[34px] bg-[#f8f9ff]">
                            <div class="flex items-center justify-between px-6 pt-3 pb-1 text-[12px] font-bold text-slate-800">
                                <span>9:41</span>
                                <span class="h-4 w-20 rounded-full bg-black"></span>
                                <span class="flex gap-1"><span class="material-symbols-outlined text-[14px]">signal_cellular_alt</span><span class="material-symbols-outlined text-[14px]">battery_full</span></span>
                            </div>
                            <div class="max-h-[600px] space-y-3 overflow-y-auto px-4 pt-3 pb-4 [scrollbar-width:none]">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <span class="rounded-full bg-violet-100 px-2 py-0.5 text-[10px] font-bold text-demo-violet">ZionBuild · Client Portal</span>
                                        <p class="mt-1.5 font-demo text-lg font-extrabold text-demo-navy">Hi, Ong family 👋</p>
                                        <p class="text-[11px] text-slate-500">Ong Residential · Antipolo, Rizal</p>
                                    </div>
                                    <span class="flex size-9 items-center justify-center rounded-full bg-violet-100 text-xs font-bold text-demo-violet">OF</span>
                                </div>

                                <div class="flex flex-col items-center rounded-2xl bg-white p-4 text-center shadow-sm">
                                    <div class="relative size-28">
                                        <svg viewBox="0 0 36 36" class="size-full -rotate-90">
                                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="#e5eeff" stroke-width="3.2" />
                                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="#006c49" stroke-width="3.2" stroke-dasharray="42 100" stroke-linecap="round" pathLength="100" />
                                        </svg>
                                        <span class="absolute inset-0 flex flex-col items-center justify-center"><span class="font-demo text-2xl font-extrabold text-demo-navy">42%</span><span class="text-[9px] font-semibold tracking-wider text-slate-500 uppercase">Done</span></span>
                                    </div>
                                    <p class="mt-1 text-xs font-bold text-demo-navy">Overall completion</p>
                                    <span class="mt-2 inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-bold text-amber-700"><span class="material-symbols-outlined text-[13px]">event</span>Next: Second-floor slab · Nov 7</span>
                                </div>

                                <div class="space-y-2 rounded-2xl bg-white p-3.5 shadow-sm">
                                    <p class="text-[13px] font-bold text-demo-navy">Latest updates</p>
                                    @foreach ($sharedUpdates as $update)
                                        <div class="flex items-center gap-2.5 rounded-xl bg-slate-50 p-2">
                                            <img src="{{ asset('images/demo/'.$update['photo'].'.jpg') }}" alt="" class="size-12 shrink-0 rounded-lg object-cover">
                                            <span><span class="block text-[10px] font-bold text-slate-500">{{ $update['date'] }}</span><span class="text-[11px] leading-tight font-semibold text-slate-800">{{ $update['note'] }}</span></span>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="rounded-2xl bg-white p-3.5 shadow-sm">
                                    <p class="text-[13px] font-bold text-demo-navy">Payments</p>
                                    <p class="mt-1 text-sm font-bold text-demo-navy">Paid {{ $peso($collected) }} <span class="font-normal text-slate-500">of {{ $peso($contractPrice) }}</span></p>
                                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full w-[40%] rounded-full bg-demo-green"></div></div>
                                    <p class="mt-1.5 text-[11px] text-amber-700">1 payment being processed</p>
                                </div>

                                <div class="flex items-center gap-2.5 rounded-2xl bg-white p-3.5 shadow-sm">
                                    <span class="material-symbols-outlined text-[22px] text-demo-blue">description</span>
                                    <span><span class="block text-[12px] font-bold text-demo-navy">Accomplishment Report</span><span class="text-[10px] text-slate-500">As of Oct 31, 2026 · 42% done</span></span>
                                </div>
                            </div>
                            <div class="flex justify-around border-t border-slate-100 bg-white px-4 py-2.5 text-[10px]">
                                @foreach (['home' => 'Home', 'photo_library' => 'Updates', 'account_balance_wallet' => 'Payments', 'more_horiz' => 'More'] as $icon => $label)
                                    <span @class(['flex flex-col items-center gap-0.5', 'font-bold text-demo-violet' => $loop->first, 'text-slate-500' => ! $loop->first])><span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>{{ $label }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endunless
    </div>

    @unless ($isDemoLocked)
    <x-slot:guide>
        @include('demo.partials.guide', ['steps' => $tourSteps])
    </x-slot:guide>
    @endunless
</x-demo.layout>
