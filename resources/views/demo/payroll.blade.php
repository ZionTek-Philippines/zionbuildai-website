@php
    $peso = fn (float $amount): string => '₱'.number_format($amount);

    // PYR-0000023: 14 workers, Mon Oct 26 – Sat Oct 31. Gross ₱58,400 (the Command Center figure), vale ₱3,500, cash advance ₱2,000, net ₱52,900.
    // absent = the day index (0 = Mon … 5 = Sat) a worker missed.
    $workers = [
        ['name' => 'Ruben Santos', 'role' => 'Foreman', 'rate' => 1_000, 'vale' => 0, 'advance' => 0, 'absent' => null, 'color' => 'bg-indigo-600'],
        ['name' => 'Mateo Cruz', 'role' => 'Mason', 'rate' => 900, 'vale' => 0, 'advance' => 1_000, 'absent' => null, 'color' => 'bg-amber-600'],
        ['name' => 'Danilo Lim', 'role' => 'Steelman', 'rate' => 900, 'vale' => 1_000, 'advance' => 0, 'absent' => null, 'color' => 'bg-rose-600'],
        ['name' => 'Arnel Ocampo', 'role' => 'Carpenter', 'rate' => 900, 'vale' => 0, 'advance' => 0, 'absent' => null, 'color' => 'bg-sky-600'],
        ['name' => 'Jessie Tan', 'role' => 'Electrician', 'rate' => 1_000, 'vale' => 0, 'advance' => 0, 'absent' => 2, 'color' => 'bg-teal-600'],
        ['name' => 'Ramon Dizon', 'role' => 'Laborer', 'rate' => 600, 'vale' => 0, 'advance' => 0, 'absent' => null],
        ['name' => 'Joel Bautista', 'role' => 'Laborer', 'rate' => 600, 'vale' => 1_000, 'advance' => 0, 'absent' => null],
        ['name' => 'Mark Villanueva', 'role' => 'Laborer', 'rate' => 600, 'vale' => 0, 'advance' => 0, 'absent' => null],
        ['name' => 'Carlo Mendoza', 'role' => 'Laborer', 'rate' => 600, 'vale' => 1_500, 'advance' => 0, 'absent' => null],
        ['name' => 'Rey Aquino', 'role' => 'Laborer', 'rate' => 600, 'vale' => 0, 'advance' => 1_000, 'absent' => null],
        ['name' => 'Noel Garcia', 'role' => 'Laborer', 'rate' => 600, 'vale' => 0, 'advance' => 0, 'absent' => null],
        ['name' => 'Jun Flores', 'role' => 'Laborer', 'rate' => 600, 'vale' => 0, 'advance' => 0, 'absent' => 4],
        ['name' => 'Bong Castillo', 'role' => 'Laborer', 'rate' => 600, 'vale' => 0, 'advance' => 0, 'absent' => null],
        ['name' => 'Edwin Ramos', 'role' => 'Laborer', 'rate' => 600, 'vale' => 0, 'advance' => 0, 'absent' => 0],
    ];
    $workers = array_map(function (array $worker): array {
        $worker['days'] = $worker['absent'] === null ? 6 : 5;
        $worker['gross'] = $worker['days'] * $worker['rate'];
        $worker['net'] = $worker['gross'] - $worker['vale'] - $worker['advance'];
        $worker['initials'] = collect(explode(' ', $worker['name']))->map(fn ($part) => $part[0])->implode('');

        return $worker;
    }, $workers);
    $totals = collect(['days', 'gross', 'vale', 'advance', 'net'])->mapWithKeys(fn ($key) => [$key => array_sum(array_column($workers, $key))]);

    $advances = [
        ['ref' => 'CA-0000039', 'date' => 'Oct 31', 'worker' => 'Noel Garcia', 'type' => 'Cash Advance', 'amount' => 2_500, 'balance' => 2_500, 'status' => 'pending'],
        ['ref' => 'CA-0000038', 'date' => 'Oct 30', 'worker' => 'Carlo Mendoza', 'type' => 'Vale', 'amount' => 1_500, 'balance' => 0, 'status' => 'done'],
        ['ref' => 'CA-0000037', 'date' => 'Oct 29', 'worker' => 'Joel Bautista', 'type' => 'Vale', 'amount' => 1_000, 'balance' => 0, 'status' => 'done'],
        ['ref' => 'CA-0000036', 'date' => 'Oct 28', 'worker' => 'Danilo Lim', 'type' => 'Vale', 'amount' => 1_000, 'balance' => 0, 'status' => 'done'],
        ['ref' => 'CA-0000034', 'date' => 'Oct 17', 'worker' => 'Rey Aquino', 'type' => 'Cash Advance', 'amount' => 3_000, 'balance' => 2_000, 'status' => 'partial'],
        ['ref' => 'CA-0000031', 'date' => 'Oct 10', 'worker' => 'Mateo Cruz', 'type' => 'Cash Advance', 'amount' => 5_000, 'balance' => 3_000, 'status' => 'partial'],
    ];

    // Matches the Command Center's Workers Payroll card.
    $runs = [
        ['ref' => 'PYR-0000023', 'period' => 'Oct 25 – Oct 31', 'employees' => 14, 'gross' => 58_400, 'vale' => 3_500, 'advance' => 2_000],
        ['ref' => 'PYR-0000022', 'period' => 'Oct 18 – Oct 24', 'employees' => 15, 'gross' => 61_250, 'vale' => 2_000, 'advance' => 1_000],
        ['ref' => 'PYR-0000021', 'period' => 'Oct 11 – Oct 17', 'employees' => 14, 'gross' => 57_800, 'vale' => 1_500, 'advance' => 0],
        ['ref' => 'PYR-0000020', 'period' => 'Oct 04 – Oct 10', 'employees' => 14, 'gross' => 55_900, 'vale' => 2_500, 'advance' => 0],
        ['ref' => 'PYR-0000019', 'period' => 'Sep 27 – Oct 03', 'employees' => 13, 'gross' => 54_300, 'vale' => 1_000, 'advance' => 0],
    ];

    $tourSteps = [
        [
            'target' => null,
            'en' => ['title' => 'Payroll without the guesswork', 'body' => 'Attendance becomes pay: days worked × daily rate, with vale and cash advances taken off automatically.'],
            'tl' => ['title' => 'Payroll na hindi hinuhulaan', 'body' => 'Ang attendance, nagiging sahod: araw na pumasok × daily rate, at kusang ibinabawas ang vale at cash advance.'],
        ],
        [
            'target' => 'payroll-kpis', 'tab' => 'week',
            'en' => ['title' => "This week's payroll", 'body' => 'Workers, gross pay, deductions and the net amount to release, for the whole crew.', 'example' => '14 workers: ₱58,400 gross, ₱52,900 net after ₱5,500 in vale and cash advances.'],
            'tl' => ['title' => 'Payroll ngayong linggo', 'body' => 'Ilang workers, gross pay, mga bawas at ang net na ire-release, para sa buong crew.', 'example' => '14 workers: ₱58,400 gross, ₱52,900 net matapos ang ₱5,500 na vale at cash advance.'],
        ],
        [
            'target' => 'payroll-table', 'tab' => 'week',
            'en' => ['title' => 'Days come from attendance', 'body' => 'No manual counting. Each worker\'s days come from their time-ins, then × their daily rate.', 'example' => 'Jessie Tan worked 5 days at ₱1,000, so his gross pay is ₱5,000.'],
            'tl' => ['title' => 'Galing sa attendance ang araw', 'body' => 'Wala nang mano-manong bilang. Galing sa time-in ang araw ng bawat worker, tapos × sa daily rate niya.', 'example' => '5 araw pumasok si Jessie Tan sa ₱1,000, kaya ₱5,000 ang gross pay niya.'],
        ],
        [
            'target' => 'payslip-link', 'tab' => 'week',
            'en' => ['title' => 'A payslip for every worker', 'body' => 'Open any payslip to see the days, rate and deductions behind the net pay. Try it: click "Payslip".', 'example' => 'Mateo: ₱5,400 gross, less ₱1,000 cash advance, ₱4,400 net.'],
            'tl' => ['title' => 'May payslip ang bawat worker', 'body' => 'Buksan ang payslip para makita ang araw, rate at mga bawas sa likod ng net pay. Subukan: i-click ang "Payslip".', 'example' => 'Si Mateo: ₱5,400 gross, bawas ₱1,000 na cash advance, ₱4,400 net.'],
        ],
        [
            'target' => 'advances', 'tab' => 'advances',
            'en' => ['title' => 'Vale and cash advances', 'body' => 'Record them once. ZionBuild deducts them at payroll and tracks what is still owed.', 'example' => 'Noel\'s ₱2,500 cash advance comes off his next payroll.'],
            'tl' => ['title' => 'Vale at cash advance', 'body' => 'I-record lang minsan. Ang ZionBuild na ang magbabawas sa payroll at magbabantay kung magkano pa ang utang.', 'example' => 'Ibabawas sa susunod na payroll ang ₱2,500 na cash advance ni Noel.'],
        ],
        [
            'target' => 'payroll-runs', 'tab' => 'runs',
            'en' => ['title' => 'Every payroll run, on record', 'body' => 'Each week\'s run with its gross, deductions and net, ready whenever you need to check.', 'example' => 'The last 5 weeks add up to ₱287,650 in gross pay.'],
            'tl' => ['title' => 'Bawat payroll run, may record', 'body' => 'Ang run ng bawat linggo kasama ang gross, bawas at net, handa kapag kailangan mong i-check.', 'example' => '₱287,650 ang kabuuang gross pay sa nakaraang 5 linggo.'],
        ],
        [
            'target' => null, 'final' => true,
            'next' => ['label' => ['en' => 'Client Billing', 'tl' => 'Client Billing'], 'url' => route('demo.client-billing')],
            'en' => ['title' => "That's Payroll", 'body' => 'Attendance, sahod, vale and cash advances, no more guessing. Want to see it with your own crew?'],
            'tl' => ['title' => 'Yan ang Payroll', 'body' => 'Attendance, sahod, vale at cash advance, hindi na kailangang hulaan. Gusto mo bang makita ito gamit ang sarili mong crew?'],
        ],
    ];

    $tabs = [
        ['key' => 'week', 'label' => "This Week's Payroll"],
        ['key' => 'advances', 'label' => 'Vale & Cash Advances'],
        ['key' => 'runs', 'label' => 'Payroll Runs'],
    ];
    $weekdays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
@endphp

<x-demo.layout title="Payroll" active="payroll" active-icon="payments">
    <div class="space-y-6">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="font-demo text-[28px] font-extrabold tracking-tight text-demo-navy">Payroll</h1>
                <p class="text-sm text-slate-500">Attendance-based payroll with vale and cash advances deducted automatically.</p>
            </div>
            <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-600">
                <span class="material-symbols-outlined text-[15px]">calendar_today</span>
                As of Sat, Oct 31, 2026
            </span>
        </div>

        <div role="tablist" class="flex gap-1 self-start overflow-x-auto rounded-full border border-slate-200/60 bg-white/80 p-1.5 shadow-sm [scrollbar-width:none] lg:inline-flex">
            @foreach ($tabs as $tab)
                <button type="button" role="tab" data-demo-tab="{{ $tab['key'] }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" @disabled($isDemoLocked && ! $loop->first) class="disabled:cursor-not-allowed disabled:opacity-50 shrink-0 rounded-full px-4 py-2 text-xs font-medium text-slate-600 transition hover:bg-slate-100 aria-selected:bg-demo-navy aria-selected:font-bold aria-selected:text-white">{{ $tab['label'] }}</button>
            @endforeach
        </div>

        {{-- ==================== This Week's Payroll ==================== --}}
        <div data-demo-panel="week" class="space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200/60 bg-white p-5 shadow-sm">
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="font-demo text-xl font-extrabold tracking-tight text-demo-navy sm:text-2xl">PYR-0000023 · Oct 25 – Oct 31, 2026</h2>
                        <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-bold text-demo-green">Posted</span>
                    </div>
                    <p class="mt-1 flex items-center gap-1.5 text-xs text-slate-500"><span class="material-symbols-outlined text-[16px] text-demo-blue">event_available</span>Days worked come from attendance (Mon–Sat)</p>
                </div>
                <span class="rounded-xl bg-slate-50 px-4 py-2 text-xs text-slate-600">Paid from <span class="font-semibold text-slate-800">Main Bank</span> · TRX-000412</span>
            </div>

            <section data-tour="payroll-kpis" class="relative grid grid-cols-2 gap-3 lg:grid-cols-5 lg:gap-4">
                <div class="rounded-2xl border border-slate-200/60 bg-white p-4 shadow-sm">
                    <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Workers</span>
                    <div class="mt-1 font-demo text-2xl font-extrabold text-demo-navy">14</div>
                    <p class="text-[11px] text-slate-500">5 skilled · 9 laborers</p>
                </div>
                <div class="rounded-2xl border border-slate-200/60 bg-white p-4 shadow-sm">
                    <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Gross pay</span>
                    <div class="mt-1 font-demo text-2xl font-extrabold text-demo-navy">{{ $peso($totals['gross']) }}</div>
                    <p class="text-[11px] text-slate-500">{{ $totals['days'] }} working days in total</p>
                </div>
                <div class="rounded-2xl border border-slate-200/60 bg-white p-4 shadow-sm">
                    <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Vale deducted</span>
                    <div class="mt-1 font-demo text-2xl font-extrabold text-amber-600">{{ $peso($totals['vale']) }}</div>
                    <p class="text-[11px] text-slate-500">3 workers</p>
                </div>
                <div class="rounded-2xl border border-slate-200/60 bg-white p-4 shadow-sm">
                    <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Cash advance deducted</span>
                    <div class="mt-1 font-demo text-2xl font-extrabold text-amber-600">{{ $peso($totals['advance']) }}</div>
                    <p class="text-[11px] text-slate-500">2 workers</p>
                </div>
                <div class="relative col-span-2 rounded-2xl border border-emerald-100 bg-emerald-50/40 p-4 shadow-sm lg:col-span-1">
                    <x-demo.info class="absolute top-3 right-3" />
                    <span class="text-[10px] font-bold tracking-wider text-emerald-800 uppercase">Net pay</span>
                    <div class="mt-1 font-demo text-2xl font-extrabold text-demo-green">{{ $peso($totals['net']) }}</div>
                    <p class="text-[11px] text-slate-500">To release to the crew</p>
                </div>
            </section>

            @if ($isDemoLocked)
                @include('demo.partials.unlock-form')
            @else

            <section data-tour="payroll-table" class="relative overflow-hidden rounded-2xl border border-slate-200/60 bg-white shadow-sm">
                <div class="p-5 sm:p-6">
                    <h2 class="font-demo text-lg font-bold text-demo-navy">Payroll Register</h2>
                    <p class="text-xs text-slate-500">Computed from each worker's attendance this week</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[860px] text-left text-xs">
                        <thead>
                            <tr class="border-y border-slate-100 bg-slate-50/70 text-[10px] font-semibold tracking-wider text-slate-500 uppercase">
                                <th class="px-5 py-3">Worker</th>
                                <th class="px-3 py-3">Role</th>
                                <th class="px-3 py-3 text-center">Days</th>
                                <th class="px-3 py-3 text-right">Rate/day</th>
                                <th class="px-3 py-3 text-right">Gross pay</th>
                                <th class="px-3 py-3 text-right">Vale</th>
                                <th class="px-3 py-3 text-right">Cash advance</th>
                                <th class="px-3 py-3 text-right">Net pay</th>
                                <th class="px-5 py-3"><span class="sr-only">Payslip</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($workers as $worker)
                                <tr>
                                    <td class="px-5 py-2.5">
                                        <div class="flex items-center gap-2.5">
                                            <span class="flex size-7 shrink-0 items-center justify-center rounded-full text-[10px] font-bold {{ $worker['color'] ?? 'bg-slate-100 text-slate-600' }} {{ isset($worker['color']) ? 'text-white' : '' }}">{{ $worker['initials'] }}</span>
                                            <span class="font-semibold text-slate-800">{{ $worker['name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2.5"><span @class(['rounded-full px-2 py-0.5 text-[10px] font-bold tracking-wide uppercase', 'bg-blue-50 text-demo-blue' => $worker['role'] !== 'Laborer', 'bg-slate-100 text-slate-500' => $worker['role'] === 'Laborer'])>{{ $worker['role'] }}</span></td>
                                    <td class="px-3 py-2.5 text-center font-semibold text-slate-800">{{ $worker['days'] }}</td>
                                    <td class="px-3 py-2.5 text-right text-slate-500">{{ $peso($worker['rate']) }}</td>
                                    <td class="px-3 py-2.5 text-right font-semibold text-slate-800">{{ $peso($worker['gross']) }}</td>
                                    <td @class(['px-3 py-2.5 text-right', 'font-semibold text-amber-600' => $worker['vale'], 'text-slate-300' => ! $worker['vale']])>{{ $worker['vale'] ? $peso($worker['vale']) : '—' }}</td>
                                    <td @class(['px-3 py-2.5 text-right', 'font-semibold text-amber-600' => $worker['advance'], 'text-slate-300' => ! $worker['advance']])>{{ $worker['advance'] ? $peso($worker['advance']) : '—' }}</td>
                                    <td class="px-3 py-2.5 text-right font-demo text-sm font-bold text-demo-green">{{ $peso($worker['net']) }}</td>
                                    <td class="px-5 py-2.5 text-right">
                                        <button type="button" data-payslip="{{ json_encode($worker) }}" @if ($worker['name'] === 'Mateo Cruz') data-tour="payslip-link" @endif class="inline-flex items-center gap-1 font-semibold text-demo-violet hover:underline">
                                            <span class="material-symbols-outlined text-[15px]">description</span>
                                            Payslip
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-slate-200 bg-slate-50/90 font-demo font-bold text-demo-navy">
                                <td class="px-5 py-3.5 text-sm">Total</td>
                                <td class="px-3 py-3.5 text-xs text-slate-500">14 workers</td>
                                <td class="px-3 py-3.5 text-center">{{ $totals['days'] }}</td>
                                <td></td>
                                <td class="px-3 py-3.5 text-right">{{ $peso($totals['gross']) }}</td>
                                <td class="px-3 py-3.5 text-right text-amber-600">{{ $peso($totals['vale']) }}</td>
                                <td class="px-3 py-3.5 text-right text-amber-600">{{ $peso($totals['advance']) }}</td>
                                <td class="px-3 py-3.5 text-right text-base text-demo-green">{{ $peso($totals['net']) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>
            @endif
        </div>

        @unless ($isDemoLocked)
        {{-- ==================== Vale & Cash Advances ==================== --}}
        <div data-demo-panel="advances" class="hidden">
            <section data-tour="advances" class="relative overflow-hidden rounded-2xl border border-slate-200/60 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 p-5 sm:p-6">
                    <div>
                        <h2 class="font-demo text-lg font-bold text-demo-navy">Vale & Cash Advances</h2>
                        <p class="text-xs text-slate-500">Outstanding balance <span class="font-bold text-amber-700">₱7,500</span> · deducted automatically at payroll</p>
                    </div>
                    <button type="button" data-demo-toast-message="In the real app, you record a vale or cash advance in seconds. It is deducted at the next payroll." data-demo-toast-message-tl="Sa totoong app, ilang segundo lang mag-record ng vale o cash advance. Ibabawas ito sa susunod na payroll." class="inline-flex items-center gap-1.5 rounded-xl bg-demo-blue px-3.5 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-blue-700">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        Record Vale / Cash Advance
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[680px] text-left text-xs">
                        <thead>
                            <tr class="border-y border-slate-100 bg-slate-50/70 text-[10px] font-semibold tracking-wider text-slate-500 uppercase">
                                <th class="px-5 py-3">Ref No.</th>
                                <th class="px-3 py-3">Date</th>
                                <th class="px-3 py-3">Worker</th>
                                <th class="px-3 py-3">Type</th>
                                <th class="px-3 py-3 text-right">Amount</th>
                                <th class="px-3 py-3 text-right">Balance</th>
                                <th class="px-5 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($advances as $advance)
                                <tr>
                                    <td class="px-5 py-3 font-semibold text-demo-navy">{{ $advance['ref'] }}</td>
                                    <td class="px-3 py-3 text-slate-500">{{ $advance['date'] }}</td>
                                    <td class="px-3 py-3 font-semibold text-slate-800">{{ $advance['worker'] }}</td>
                                    <td class="px-3 py-3 text-slate-600">{{ $advance['type'] }}</td>
                                    <td class="px-3 py-3 text-right font-semibold text-slate-800">{{ $peso($advance['amount']) }}</td>
                                    <td @class(['px-3 py-3 text-right font-semibold', 'text-amber-700' => $advance['balance'], 'text-slate-400' => ! $advance['balance']])>{{ $peso($advance['balance']) }}</td>
                                    <td class="px-5 py-3 text-center">
                                        @if ($advance['status'] === 'pending')
                                            <span class="rounded-full bg-amber-50 px-2.5 py-0.5 text-[11px] font-semibold text-amber-800">Next payroll</span>
                                        @elseif ($advance['status'] === 'partial')
                                            <span class="rounded-full bg-blue-50 px-2.5 py-0.5 text-[11px] font-semibold text-demo-blue">Partly deducted</span>
                                        @else
                                            <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-semibold text-demo-green">Deducted</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        {{-- ==================== Payroll Runs ==================== --}}
        <div data-demo-panel="runs" class="hidden">
            <section data-tour="payroll-runs" class="relative overflow-hidden rounded-2xl border border-slate-200/60 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 p-5 sm:p-6">
                    <div>
                        <h2 class="font-demo text-lg font-bold text-demo-navy">Payroll Runs</h2>
                        <p class="text-xs text-slate-500">Every week's payroll, on record</p>
                    </div>
                    <button type="button" data-demo-toast-message="In the real app, Run Payroll picks the week, pulls attendance and applies pending deductions for you." data-demo-toast-message-tl="Sa totoong app, pipiliin ng Run Payroll ang linggo, kukunin ang attendance at ibabawas ang mga pending na vale at cash advance." class="inline-flex items-center gap-1.5 rounded-xl bg-demo-blue px-3.5 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-blue-700">
                        <span class="material-symbols-outlined text-[16px]">play_arrow</span>
                        Run Payroll
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[720px] text-left text-xs">
                        <thead>
                            <tr class="border-y border-slate-100 bg-slate-50/70 text-[10px] font-semibold tracking-wider text-slate-500 uppercase">
                                <th class="px-5 py-3">Run Ref</th>
                                <th class="px-3 py-3">Period</th>
                                <th class="px-3 py-3 text-center">Employees</th>
                                <th class="px-3 py-3 text-right">Gross</th>
                                <th class="px-3 py-3 text-right">Vale</th>
                                <th class="px-3 py-3 text-right">Cash advance</th>
                                <th class="px-3 py-3 text-right">Net pay</th>
                                <th class="px-5 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($runs as $run)
                                <tr @class(['bg-blue-50/30' => $loop->first])>
                                    <td class="px-5 py-3 font-semibold text-demo-navy">{{ $run['ref'] }}</td>
                                    <td class="px-3 py-3 text-slate-600">{{ $run['period'] }}</td>
                                    <td class="px-3 py-3 text-center text-slate-700">{{ $run['employees'] }}</td>
                                    <td class="px-3 py-3 text-right font-semibold text-slate-800">{{ $peso($run['gross']) }}</td>
                                    <td class="px-3 py-3 text-right text-amber-600">{{ $peso($run['vale']) }}</td>
                                    <td class="px-3 py-3 text-right text-amber-600">{{ $peso($run['advance']) }}</td>
                                    <td class="px-3 py-3 text-right font-demo font-bold text-demo-green">{{ $peso($run['gross'] - $run['vale'] - $run['advance']) }}</td>
                                    <td class="px-5 py-3 text-center"><span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-semibold text-demo-green">Posted</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
        @endunless
    </div>

    @unless ($isDemoLocked)
    {{-- Payslip drawer (filled from the clicked worker) --}}
    <div data-payslip-drawer class="fixed inset-0 z-[70] hidden justify-end bg-demo-navy/30 backdrop-blur-[2px]">
        <aside class="flex h-full w-full max-w-sm flex-col overflow-y-auto bg-white shadow-2xl">
            <div class="flex items-start justify-between gap-3 border-b border-slate-100 bg-slate-50/70 p-5">
                <div>
                    <p class="text-[10px] font-bold tracking-wider text-demo-blue uppercase">Payslip · PYR-0000023</p>
                    <h3 data-slip-name class="font-demo text-lg font-extrabold text-demo-navy"></h3>
                    <p class="text-xs text-slate-500"><span data-slip-role></span> · Oct 25 – Oct 31, 2026</p>
                </div>
                <button type="button" data-slip-close aria-label="Close" class="flex size-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:text-slate-800"><span class="material-symbols-outlined text-[18px]">close</span></button>
            </div>
            <div class="flex-1 space-y-4 p-5 text-sm">
                <div>
                    <p class="mb-2 text-[10px] font-bold tracking-wider text-slate-400 uppercase">Attendance</p>
                    <div class="grid grid-cols-6 gap-1.5">
                        @foreach ($weekdays as $weekday)
                            <span data-slip-day="{{ $loop->index }}" class="rounded-lg py-1.5 text-center text-[11px] font-semibold">{{ $weekday }}</span>
                        @endforeach
                    </div>
                </div>
                <dl class="space-y-2 rounded-xl border border-slate-200/60 p-4 text-xs">
                    <div class="flex justify-between"><dt class="text-slate-500"><span data-slip-days></span> days × <span data-slip-rate></span></dt><dd data-slip-gross class="font-semibold text-slate-800"></dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Less vale</dt><dd data-slip-vale class="font-semibold text-amber-600"></dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Less cash advance</dt><dd data-slip-advance class="font-semibold text-amber-600"></dd></div>
                    <div class="flex items-center justify-between border-t border-slate-100 pt-3"><dt class="font-semibold text-slate-700">Net pay</dt><dd data-slip-net class="font-demo text-xl font-extrabold text-demo-green"></dd></div>
                </dl>
            </div>
            <div class="border-t border-slate-100 p-4">
                <button type="button" data-demo-toast-message="In the real app, you can print the payslip to hand to the worker." data-demo-toast-message-tl="Sa totoong app, puwede mong i-print ang payslip para iabot sa worker." class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Print payslip</button>
            </div>
        </aside>
    </div>

    <x-slot:guide>
        @include('demo.partials.guide', ['steps' => $tourSteps])
    </x-slot:guide>
    @endunless
</x-demo.layout>
