@php
    $peso = fn (float $amount): string => '₱'.number_format($amount);

    // Budgets total the ₱7,140,000 estimated project cost. Spent = payroll ₱1,148,600 + paid expenses ₱2,196,450 = ₱3,345,050.
    $categories = [
        ['name' => 'Labor (Payroll)', 'budget' => 2_350_000, 'spent' => 1_148_600, 'unpaid' => 0, 'status' => 'watch'],
        ['name' => 'Structural Materials', 'budget' => 2_050_000, 'spent' => 1_512_300, 'unpaid' => 6_850, 'status' => 'ok', 'note' => 'structure mostly done'],
        ['name' => 'Masonry Materials', 'budget' => 520_000, 'spent' => 318_450, 'unpaid' => 0, 'status' => 'ok'],
        ['name' => 'Formworks & Scaffolding', 'budget' => 340_000, 'spent' => 214_900, 'unpaid' => 0, 'status' => 'ok'],
        ['name' => 'Electrical & Plumbing', 'budget' => 560_000, 'spent' => 58_200, 'unpaid' => 0, 'status' => 'ok'],
        ['name' => 'Roofing Materials', 'budget' => 420_000, 'spent' => 0, 'unpaid' => 0, 'status' => 'idle'],
        ['name' => 'Finishes', 'budget' => 740_000, 'spent' => 0, 'unpaid' => 0, 'status' => 'idle'],
        ['name' => 'Equipment & Hauling', 'budget' => 160_000, 'spent' => 92_600, 'unpaid' => 57_400, 'status' => 'watch'],
    ];
    $totals = [
        'budget' => array_sum(array_column($categories, 'budget')),
        'spent' => array_sum(array_column($categories, 'spent')),
        'unpaid' => array_sum(array_column($categories, 'unpaid')),
    ];
    $totals['remaining'] = $totals['budget'] - $totals['spent'] - $totals['unpaid'];
    $largestBudget = max(array_column($categories, 'budget'));

    $expenses = [
        ['ref' => 'EXP-0000114', 'date' => 'Oct 31, 2026', 'category' => 'Structural Materials', 'description' => 'Tie Wire #16, Rebar Spacers', 'vendor' => 'Dela Paz Hardware', 'amount' => 6_850, 'paid' => false, 'by' => 'Ruben Santos', 'items' => [['Tie Wire #16', '10 rolls', 3_250], ['Rebar Spacers', '600 pcs', 3_600]]],
        ['ref' => 'EXP-0000113', 'date' => 'Oct 31, 2026', 'category' => 'Equipment & Hauling', 'description' => 'Concrete Pump Rental (Nov 7 pour)', 'vendor' => 'Antipolo Ready-Mix', 'amount' => 57_400, 'paid' => false, 'by' => 'Engr. Paolo Reyes', 'items' => [['Concrete pump, 1 day (Nov 7 pour)', '1 day', 57_400]]],
        ['ref' => 'EXP-0000112', 'date' => 'Oct 30, 2026', 'category' => 'Structural Materials', 'description' => 'Ready-mix Concrete 4,000 psi', 'vendor' => 'Antipolo Ready-Mix', 'amount' => 186_500, 'paid' => true, 'by' => 'Ruben Santos', 'items' => [['Ready-mix Concrete 4,000 psi', '25 cu.m × ₱7,460', 186_500]]],
        ['ref' => 'EXP-0000111', 'date' => 'Oct 29, 2026', 'category' => 'Structural Materials', 'description' => 'Deformed Bars 12mm, Tie Wire #16', 'vendor' => 'Luzon Steel Center', 'amount' => 48_720, 'paid' => true, 'by' => 'Ruben Santos', 'items' => [['Deformed Bars 12mm', '120 pcs', 46_200], ['Tie Wire #16', '8 rolls', 2_520]]],
        ['ref' => 'EXP-0000109', 'date' => 'Oct 27, 2026', 'category' => 'Formworks & Scaffolding', 'description' => 'Coco Lumber 2x2x10, Marine Plywood ½"', 'vendor' => 'Marikina Lumber Yard', 'amount' => 15_120, 'paid' => true, 'by' => 'Ruben Santos', 'items' => [['Coco Lumber 2x2x10', '60 pcs', 5_520], ['Marine Plywood ½"', '16 sheets', 9_600]]],
        ['ref' => 'EXP-0000108', 'date' => 'Oct 24, 2026', 'category' => 'Structural Materials', 'description' => 'Gravel, Sand', 'vendor' => 'San Isidro Aggregates', 'amount' => 79_000, 'paid' => true, 'by' => 'Ruben Santos', 'items' => [['Gravel G-1', '8 cu.m', 44_000], ['Sand', '7 cu.m', 35_000]]],
        ['ref' => 'EXP-0000105', 'date' => 'Oct 21, 2026', 'category' => 'Electrical & Plumbing', 'description' => 'Electrical Pipe ½", Junction Box', 'vendor' => 'Dela Paz Hardware', 'amount' => 12_600, 'paid' => true, 'by' => 'Engr. Paolo Reyes', 'items' => [['Electrical Pipe ½"', '40 pcs', 8_400], ['Junction Box', '60 pcs', 4_200]]],
    ];

    $accounts = [
        ['name' => 'Main Bank', 'type' => 'Checking · ••••4821', 'icon' => 'account_balance', 'balance' => 2_486_730, 'opening' => 1_800_000, 'default' => true],
        ['name' => 'Project Fund', 'type' => 'Savings · ••••0917', 'icon' => 'savings', 'balance' => 1_250_000, 'opening' => 1_250_000, 'default' => false],
        ['name' => 'Petty Cash', 'type' => 'Cash on hand', 'icon' => 'payments', 'balance' => 18_500, 'opening' => 5_000, 'default' => false],
    ];

    $transactions = [
        ['date' => 'Oct 31, 2026', 'ref' => 'TRX-000412', 'type' => 'Payroll', 'party' => 'PYR-0000023', 'detail' => 'Net pay · Oct 25–31 · 14 workers', 'direction' => 'out', 'amount' => 52_900],
        ['date' => 'Oct 30, 2026', 'ref' => 'TRX-000411', 'type' => 'Expense payment', 'party' => 'Antipolo Ready-Mix', 'detail' => 'EXP-0000112 · Ready-mix concrete', 'direction' => 'out', 'amount' => 186_500],
        ['date' => 'Oct 29, 2026', 'ref' => 'TRX-000409', 'type' => 'Expense payment', 'party' => 'Luzon Steel Center', 'detail' => 'EXP-0000111 · Deformed bars', 'direction' => 'out', 'amount' => 48_720],
        ['date' => 'Oct 27, 2026', 'ref' => 'TRX-000405', 'type' => 'Transfer', 'party' => 'Main Bank → Petty Cash', 'detail' => 'Site petty cash', 'direction' => 'transfer', 'amount' => 20_000],
        ['date' => 'Oct 24, 2026', 'ref' => 'TRX-000398', 'type' => 'Expense payment', 'party' => 'San Isidro Aggregates', 'detail' => 'EXP-0000108 · Gravel, sand', 'direction' => 'out', 'amount' => 79_000],
        ['date' => 'Aug 28, 2026', 'ref' => 'TRX-000301', 'type' => 'Client payment', 'party' => 'Ong family', 'detail' => 'CP-0000002 · Progress Billing #1', 'direction' => 'in', 'amount' => 1_020_000],
    ];

    $tourSteps = [
        [
            'target' => null,
            'en' => ['title' => 'Finances: every peso, accounted for', 'body' => 'Budget vs actual, every expense with its receipt details, and the money moving through your bank and cash accounts.'],
            'tl' => ['title' => 'Finances: bawat piso, may record', 'body' => 'Budget vs actual, bawat gastos kasama ang detalye ng resibo, at ang galaw ng pera sa bank at cash accounts mo.'],
        ],
        [
            'target' => 'finance-kpis', 'tab' => 'budget',
            'en' => ['title' => 'Budget at a glance', 'body' => 'Your project budget, what has been spent, bills still for payment, and what is left.', 'example' => '₱3,345,050 spent of the ₱7,140,000 budget (47%) while the project is 42% done.'],
            'tl' => ['title' => 'Budget, isang tingin', 'body' => 'Ang budget ng project, magkano na ang nagastos, mga bill na babayaran pa, at ang natitira.', 'example' => '₱3,345,050 na ang nagastos sa ₱7,140,000 na budget (47%) habang 42% pa lang tapos ang project.'],
        ],
        [
            'target' => 'budget-alert', 'tab' => 'budget',
            'en' => ['title' => 'An early warning', 'body' => 'When spending runs ahead of progress, you see it now, not when the budget runs out.', 'example' => 'Labor is at 49% of its budget at 42% completion. Worth checking crew size before the slab pour.'],
            'tl' => ['title' => 'Maagang babala', 'body' => 'Kapag mas mabilis ang gastos kaysa progress, makikita mo agad, hindi kapag ubos na ang budget.', 'example' => '49% na ng budget ang Labor pero 42% pa lang ang tapos. Silipin muna ang dami ng tao bago ang buhos ng slab.'],
        ],
        [
            'target' => 'budget-table', 'tab' => 'budget',
            'en' => ['title' => 'Budget vs actual, by category', 'body' => 'Each budget category with what is spent, unpaid and left, and a status so problems stand out.', 'example' => 'Structural Materials is 74% used, but the structure is mostly done, so it is on track.'],
            'tl' => ['title' => 'Budget vs actual, bawat category', 'body' => 'Bawat budget category kasama ang nagastos, hindi pa bayad at natitira, may status para lumitaw agad ang problema.', 'example' => '74% na ang nagamit sa Structural Materials, pero halos tapos na ang structure, kaya on track.'],
        ],
        [
            'target' => 'expense-table', 'tab' => 'expenses',
            'en' => ['title' => 'Every expense, recorded', 'body' => 'Each purchase with its category, materials, vendor and amount. Click a row to see the line items.', 'example' => 'EXP-0000112: ₱186,500 of ready-mix concrete from Antipolo Ready-Mix on Oct 30.'],
            'tl' => ['title' => 'Bawat gastos, may record', 'body' => 'Bawat bili kasama ang category, materials, vendor at halaga. I-click ang row para makita ang mga item.', 'example' => 'EXP-0000112: ₱186,500 na ready-mix concrete mula sa Antipolo Ready-Mix noong Oct 30.'],
        ],
        [
            'target' => 'expense-status', 'tab' => 'expenses',
            'en' => ['title' => 'Paid vs for payment', 'body' => 'Unpaid bills stay visible until they are paid, so nothing slips through.', 'example' => 'Two bills (₱64,250) are waiting, including the concrete pump for the Nov 7 pour.'],
            'tl' => ['title' => 'Bayad vs babayaran pa', 'body' => 'Nakikita ang mga hindi pa bayad hangga\'t hindi nababayaran, kaya walang nakakalimutan.', 'example' => 'May dalawang bill (₱64,250) na naghihintay, kasama ang concrete pump para sa buhos sa Nov 7.'],
        ],
        [
            'target' => 'bank-accounts', 'tab' => 'bank',
            'en' => ['title' => 'Bank and cash accounts', 'body' => 'Balances your team records in ZionBuild. It is not connected to your bank; it is your own clear record.', 'example' => 'Main Bank shows ₱2,486,730, the same Company Cash on Hand you saw on the Command Center.'],
            'tl' => ['title' => 'Bank at cash accounts', 'body' => 'Mga balance na nire-record ng team mo sa ZionBuild. Hindi ito naka-connect sa bangko; malinaw mong sariling record ito.', 'example' => '₱2,486,730 ang Main Bank, parehong Company Cash on Hand na nakita mo sa Command Center.'],
        ],
        [
            'target' => 'bank-statement', 'tab' => 'bank',
            'en' => ['title' => 'Money in, money out', 'body' => 'Client payments, expense payments, payroll and transfers, each with a reference you can trace.', 'example' => 'Aug 28: ₱1,020,000 in from the Ong family for Progress Billing #1.'],
            'tl' => ['title' => 'Perang pumasok at lumabas', 'body' => 'Bayad ng client, bayad sa gastos, payroll at transfers, bawat isa may reference na masusundan.', 'example' => 'Aug 28: ₱1,020,000 ang pumasok mula sa pamilyang Ong para sa Progress Billing #1.'],
        ],
        [
            'target' => null, 'final' => true,
            'next' => ['label' => ['en' => 'Payroll', 'tl' => 'Payroll'], 'url' => route('demo.payroll')],
            'en' => ['title' => "That's Finances", 'body' => 'Budget, expenses and cash in one place, with early warnings before money becomes a problem. Want to see it with your own projects?'],
            'tl' => ['title' => 'Yan ang Finances', 'body' => 'Budget, gastos at cash sa iisang lugar, may maagang babala bago maging problema ang pera. Gusto mo bang makita ito gamit ang sarili mong projects?'],
        ],
    ];

    $tabs = [
        ['key' => 'budget', 'label' => 'Budget vs Actual', 'icon' => 'pie_chart'],
        ['key' => 'expenses', 'label' => 'Expenses', 'icon' => 'receipt_long'],
        ['key' => 'bank', 'label' => 'Bank & Cash', 'icon' => 'account_balance'],
    ];
@endphp

<x-demo.layout title="Finances" active="finances" active-icon="account_balance_wallet">
    <div class="space-y-6">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="font-demo text-[28px] font-extrabold tracking-tight text-demo-navy">Finances</h1>
                <p class="text-sm text-slate-500">Budget vs actual, every expense, and where the money moved.</p>
            </div>
            <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-600">
                <span class="material-symbols-outlined text-[15px]">calendar_today</span>
                As of Sat, Oct 31, 2026
            </span>
        </div>

        <div role="tablist" class="flex gap-1 self-start overflow-x-auto rounded-full border border-slate-200/60 bg-white/80 p-1.5 shadow-sm [scrollbar-width:none] lg:inline-flex">
            @foreach ($tabs as $tab)
                <button type="button" role="tab" data-demo-tab="{{ $tab['key'] }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" @disabled($isDemoLocked && ! $loop->first) class="disabled:cursor-not-allowed disabled:opacity-50 inline-flex shrink-0 items-center gap-1.5 rounded-full px-4 py-2 text-xs font-medium text-slate-600 transition hover:bg-slate-100 aria-selected:bg-demo-navy aria-selected:font-bold aria-selected:text-white">
                    <span class="material-symbols-outlined text-[16px]">{{ $tab['icon'] }}</span>
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>

        {{-- ==================== Budget vs Actual ==================== --}}
        <div data-demo-panel="budget" class="space-y-6">
            <section data-tour="finance-kpis" class="relative grid grid-cols-2 gap-3 lg:grid-cols-4 lg:gap-4">
                <div class="rounded-2xl border border-slate-200/60 bg-white p-4 shadow-sm sm:p-5">
                    <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase sm:text-[11px]">Project budget</span>
                    <div class="mt-2 font-demo text-xl font-extrabold text-demo-navy sm:text-2xl">{{ $peso($totals['budget']) }}</div>
                    <p class="mt-1 text-xs text-slate-500">Estimated project cost</p>
                </div>
                <div class="rounded-2xl border border-slate-200/60 bg-white p-4 shadow-sm sm:p-5">
                    <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase sm:text-[11px]">Spent to date</span>
                    <div class="mt-2 font-demo text-xl font-extrabold text-demo-navy sm:text-2xl">{{ $peso($totals['spent']) }}</div>
                    <p class="mt-1 text-xs text-slate-500"><span class="font-semibold text-slate-700">47% of budget</span> · project 42% done</p>
                </div>
                <div class="rounded-2xl border border-slate-200/60 bg-white p-4 shadow-sm sm:p-5">
                    <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase sm:text-[11px]">For payment</span>
                    <div class="mt-2 font-demo text-xl font-extrabold text-amber-600 sm:text-2xl">{{ $peso($totals['unpaid']) }}</div>
                    <p class="mt-1 text-xs text-amber-700">2 unpaid expenses</p>
                </div>
                <div class="relative rounded-2xl border border-slate-200/60 bg-white p-4 shadow-sm sm:p-5">
                    <x-demo.info class="absolute top-3 right-3" />
                    <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase sm:text-[11px]">Remaining budget</span>
                    <div class="mt-2 font-demo text-xl font-extrabold text-demo-green sm:text-2xl">{{ $peso($totals['remaining']) }}</div>
                    <p class="mt-1 text-xs text-slate-500">after unpaid bills</p>
                </div>
            </section>

            @if ($isDemoLocked)
                @include('demo.partials.unlock-form')
            @else

            <div data-tour="budget-alert" class="relative flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-3.5">
                <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-700"><span class="material-symbols-outlined text-[18px]">lightbulb</span></span>
                <p class="text-sm font-medium text-amber-900">Spending is 5 points ahead of progress, mostly Labor. Worth a look before the second-floor slab pour.</p>
            </div>

            <section data-tour="budget-table" class="relative rounded-2xl border border-slate-200/60 bg-white p-5 shadow-sm sm:p-6">
                <x-demo.info class="absolute top-5 right-5" />
                <h2 class="pr-8 font-demo text-lg font-bold text-demo-navy">Budget vs Actual</h2>
                <p class="text-xs text-slate-500">Each budget category: spent, unpaid and what is left</p>

                <div class="-mx-2 mt-4 overflow-x-auto px-2">
                    <table class="w-full min-w-[760px] text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-semibold tracking-wider text-slate-400 uppercase">
                                <th class="px-3 py-2.5">Budget category</th>
                                <th class="px-3 py-2.5 text-right">Budget</th>
                                <th class="px-3 py-2.5 text-right">Spent</th>
                                <th class="px-3 py-2.5 text-right">Unpaid</th>
                                <th class="px-3 py-2.5 text-right">Remaining</th>
                                <th class="w-44 px-3 py-2.5">% Used</th>
                                <th class="px-3 py-2.5 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($categories as $category)
                                @php($used = ($category['spent'] + $category['unpaid']) / $category['budget'] * 100)
                                <tr>
                                    <td class="px-3 py-3">
                                        <div class="flex items-center gap-2 font-semibold text-slate-800">
                                            <span @class(['size-2 rounded-full', 'bg-amber-500' => $category['status'] === 'watch', 'bg-demo-green' => $category['status'] === 'ok', 'bg-slate-300' => $category['status'] === 'idle'])></span>
                                            {{ $category['name'] }}
                                        </div>
                                        @isset($category['note'])
                                            <div class="ml-4 text-[11px] text-slate-400">{{ $category['note'] }}</div>
                                        @endisset
                                    </td>
                                    <td class="px-3 py-3 text-right font-demo font-semibold text-slate-700">{{ $peso($category['budget']) }}</td>
                                    <td class="px-3 py-3 text-right font-demo font-semibold text-slate-700">{{ $peso($category['spent']) }}</td>
                                    <td @class(['px-3 py-3 text-right font-demo font-semibold', 'text-amber-700' => $category['unpaid'], 'text-slate-300' => ! $category['unpaid']])>{{ $category['unpaid'] ? $peso($category['unpaid']) : '—' }}</td>
                                    <td class="px-3 py-3 text-right font-demo font-semibold text-slate-700">{{ $peso($category['budget'] - $category['spent'] - $category['unpaid']) }}</td>
                                    <td class="px-3 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="flex h-2 flex-1 overflow-hidden rounded-full bg-slate-100">
                                                <div @class(['h-full', 'bg-amber-500' => $category['status'] === 'watch' && ! $category['unpaid'], 'bg-demo-blue' => $category['status'] !== 'watch' || $category['unpaid']]) style="width: {{ $category['spent'] / $category['budget'] * 100 }}%"></div>
                                                <div class="h-full bg-amber-400" style="width: {{ $category['unpaid'] / $category['budget'] * 100 }}%"></div>
                                            </div>
                                            <span class="w-9 text-right font-semibold text-slate-700">{{ round($used) }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        @if ($category['status'] === 'watch')
                                            <span class="rounded-full bg-amber-50 px-2.5 py-0.5 text-[11px] font-semibold text-amber-800">Watch</span>
                                        @elseif ($category['status'] === 'ok')
                                            <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-semibold text-demo-green">On track</span>
                                        @else
                                            <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-[11px] font-medium text-slate-500">Not started</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-slate-200 bg-slate-50/90 font-demo font-bold text-demo-navy">
                                <td class="px-3 py-3.5 text-sm">Total</td>
                                <td class="px-3 py-3.5 text-right">{{ $peso($totals['budget']) }}</td>
                                <td class="px-3 py-3.5 text-right">{{ $peso($totals['spent']) }}</td>
                                <td class="px-3 py-3.5 text-right text-amber-700">{{ $peso($totals['unpaid']) }}</td>
                                <td class="px-3 py-3.5 text-right text-demo-green">{{ $peso($totals['remaining']) }}</td>
                                <td class="px-3 py-3.5">{{ round(($totals['spent'] + $totals['unpaid']) / $totals['budget'] * 100) }}%</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200/60 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h2 class="font-demo text-lg font-bold text-demo-navy">Budget vs Spent by Category</h2>
                        <p class="text-xs text-slate-500">Bar length shows each category's budget</p>
                    </div>
                    <div class="flex items-center gap-3 rounded-full bg-slate-50 px-3 py-1.5 text-[11px] text-slate-600">
                        <span class="flex items-center gap-1.5"><span class="size-2.5 rounded-sm bg-slate-200"></span>Budget</span>
                        <span class="flex items-center gap-1.5"><span class="size-2.5 rounded-sm bg-demo-blue"></span>Spent</span>
                        <span class="flex items-center gap-1.5"><span class="size-2.5 rounded-sm bg-amber-400"></span>Unpaid</span>
                    </div>
                </div>
                <div class="mt-5 space-y-3.5">
                    @foreach ($categories as $category)
                        <div class="grid grid-cols-[minmax(0,9rem)_1fr] items-center gap-3 sm:grid-cols-[12rem_1fr_9rem]">
                            <span class="truncate text-xs font-semibold text-slate-700">{{ $category['name'] }}</span>
                            <div class="h-5 rounded-md bg-slate-50">
                                <div class="flex h-full overflow-hidden rounded-md bg-slate-200" style="width: {{ $category['budget'] / $largestBudget * 100 }}%">
                                    <div class="h-full bg-demo-blue" style="width: {{ $category['spent'] / $category['budget'] * 100 }}%"></div>
                                    <div class="h-full bg-amber-400" style="width: {{ $category['unpaid'] / $category['budget'] * 100 }}%"></div>
                                </div>
                            </div>
                            <span class="col-span-2 text-right text-[11px] text-slate-500 sm:col-span-1"><span class="font-bold text-slate-800">{{ $peso($category['spent'] + $category['unpaid']) }}</span> / {{ $peso($category['budget']) }}</span>
                        </div>
                    @endforeach
                </div>
            </section>
            @endif
        </div>

        @unless ($isDemoLocked)
        {{-- ==================== Expenses ==================== --}}
        <div data-demo-panel="expenses" class="hidden space-y-4">
            <div class="flex flex-col gap-3 rounded-2xl border border-slate-200/80 bg-white p-3 shadow-sm lg:flex-row lg:items-center lg:justify-between">
                <div class="flex flex-wrap items-center gap-2 text-xs">
                    <span class="hidden items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50/60 px-3 py-1.5 text-slate-400 md:inline-flex"><span class="material-symbols-outlined text-[16px]">search</span>Search expenses…</span>
                    <span class="inline-flex items-center gap-1 rounded-xl border border-slate-200 px-3 py-1.5 text-slate-700"><span class="text-slate-400">Category:</span><span class="font-semibold">All</span></span>
                    <span class="inline-flex items-center gap-1 rounded-xl border border-slate-200 px-3 py-1.5 text-slate-700"><span class="text-slate-400">Status:</span><span class="font-semibold">All</span></span>
                    <span class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 px-3 py-1.5 text-slate-700"><span class="material-symbols-outlined text-[15px] text-slate-400">calendar_month</span>Oct 21 – Oct 31</span>
                </div>
                <button type="button" data-demo-toast-message="In the real app, New Expense records a purchase with its items, vendor, budget category and receipt photo." data-demo-toast-message-tl="Sa totoong app, nire-record ng New Expense ang bili kasama ang items, vendor, budget category at photo ng resibo." class="inline-flex items-center gap-1.5 self-start rounded-xl bg-demo-blue px-3.5 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-blue-700">
                    <span class="material-symbols-outlined text-[16px]">add</span>
                    New Expense
                </button>
            </div>

            <div data-tour="expense-status" class="relative flex flex-wrap gap-2">
                <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3.5 py-1.5 text-xs font-bold text-demo-green"><span class="size-2 rounded-full bg-demo-green"></span>Paid ₱2,196,450</span>
                <span class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3.5 py-1.5 text-xs font-bold text-amber-800"><span class="size-2 rounded-full bg-amber-500"></span>Unpaid ₱64,250</span>
            </div>

            <section data-tour="expense-table" class="relative overflow-hidden rounded-2xl border border-slate-200/60 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[820px] text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-[10px] font-semibold tracking-wider text-slate-500 uppercase">
                                <th class="px-4 py-3">Expense No.</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Category</th>
                                <th class="px-4 py-3">Materials / Description</th>
                                <th class="px-4 py-3">Vendor</th>
                                <th class="px-4 py-3 text-right">Amount</th>
                                <th class="px-4 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($expenses as $expense)
                                <tr data-expense="{{ json_encode($expense) }}" tabindex="0" class="cursor-pointer transition hover:bg-blue-50/50 focus:bg-blue-50/50 focus:outline-none">
                                    <td class="px-4 py-3 font-semibold text-demo-navy">{{ $expense['ref'] }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-slate-500">{{ $expense['date'] }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-slate-700">{{ $expense['category'] }}</td>
                                    <td class="px-4 py-3 font-medium text-demo-blue">{{ $expense['description'] }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-slate-500">{{ $expense['vendor'] }}</td>
                                    <td @class(['px-4 py-3 text-right font-demo font-bold whitespace-nowrap', 'text-amber-700' => ! $expense['paid'], 'text-demo-navy' => $expense['paid']])>{{ $peso($expense['amount']) }}</td>
                                    <td class="px-4 py-3 text-center" data-expense-status>
                                        @if ($expense['paid'])
                                            <span class="rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-[11px] font-bold text-demo-green">Paid</span>
                                        @else
                                            <span class="rounded-full border border-amber-200 bg-amber-50 px-2.5 py-0.5 text-[11px] font-bold text-amber-800">Unpaid</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="border-t border-slate-100 bg-slate-50/50 px-4 py-3 text-xs text-slate-500">Click any expense to see its line items</p>
            </section>
        </div>

        {{-- ==================== Bank & Cash ==================== --}}
        <div data-demo-panel="bank" class="hidden space-y-6">
            <p class="flex items-center gap-2 rounded-xl border border-slate-200/60 bg-white/80 px-4 py-2.5 text-xs text-slate-600">
                <span class="material-symbols-outlined text-[16px] text-demo-blue">info</span>
                Recorded in ZionBuild by your team. Not connected to your bank.
            </p>

            <section data-tour="bank-accounts" class="relative grid gap-4 md:grid-cols-3">
                @foreach ($accounts as $account)
                    <div class="relative overflow-hidden rounded-2xl border border-slate-200/60 bg-white p-5 shadow-sm">
                        @if ($account['default'])
                            <span class="absolute inset-x-0 top-0 h-1 bg-demo-violet"></span>
                        @endif
                        <div class="flex items-center justify-between gap-2">
                            <span class="flex items-center gap-2 font-demo text-base font-bold text-demo-navy">
                                <span class="material-symbols-outlined text-[22px] text-demo-violet">{{ $account['icon'] }}</span>
                                {{ $account['name'] }}
                            </span>
                            @if ($account['default'])
                                <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-demo-green">Default</span>
                            @endif
                        </div>
                        <p class="mt-1 text-xs text-slate-500">{{ $account['type'] }}</p>
                        <span class="mt-4 block text-[10px] font-bold tracking-wider text-slate-400 uppercase">Current balance</span>
                        <div class="font-demo text-2xl font-extrabold text-demo-navy">{{ $peso($account['balance']) }}</div>
                        <p class="mt-2 border-t border-slate-100 pt-2 text-[11px] text-slate-400">Opening balance {{ $peso($account['opening']) }}</p>
                    </div>
                @endforeach
            </section>

            <section data-tour="bank-statement" class="relative overflow-hidden rounded-2xl border border-slate-200/60 bg-white shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-3 p-5 sm:p-6">
                    <div>
                        <h2 class="font-demo text-lg font-bold text-demo-navy">Bank Statement — Main Bank</h2>
                        <p class="text-xs text-slate-500">Client payments, expense payments, payroll and transfers</p>
                    </div>
                    <div class="flex gap-2 text-xs">
                        <span class="rounded-lg border border-slate-200 px-3 py-1.5 text-slate-700">This month</span>
                        <span class="rounded-lg border border-slate-200 px-3 py-1.5 text-slate-700">All types</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[820px] text-left text-xs">
                        <thead>
                            <tr class="border-y border-slate-100 bg-slate-50/70 text-[10px] font-semibold tracking-wider text-slate-500 uppercase">
                                <th class="px-5 py-3">Date</th>
                                <th class="px-4 py-3">Reference</th>
                                <th class="px-4 py-3">Type</th>
                                <th class="px-4 py-3">Payee / Payer</th>
                                <th class="px-4 py-3 text-center">In/Out</th>
                                <th class="px-4 py-3 text-right">Amount</th>
                                <th class="px-5 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($transactions as $transaction)
                                <tr @class(['bg-emerald-50/30' => $transaction['direction'] === 'in'])>
                                    <td class="px-5 py-3 whitespace-nowrap text-slate-700">{{ $transaction['date'] }}</td>
                                    <td class="px-4 py-3 font-semibold whitespace-nowrap text-demo-violet">{{ $transaction['ref'] }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap"><span class="rounded bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-700">{{ $transaction['type'] }}</span></td>
                                    <td class="px-4 py-3"><div class="font-semibold text-slate-800">{{ $transaction['party'] }}</div><div class="text-[11px] text-slate-500">{{ $transaction['detail'] }}</div></td>
                                    <td class="px-4 py-3 text-center">
                                        @if ($transaction['direction'] === 'in')
                                            <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-bold text-demo-green">↑ In</span>
                                        @elseif ($transaction['direction'] === 'out')
                                            <span class="rounded-full bg-red-50 px-2 py-0.5 text-[11px] font-bold text-red-700">↓ Out</span>
                                        @else
                                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-bold text-slate-500">⇄ Transfer</span>
                                        @endif
                                    </td>
                                    <td @class(['px-4 py-3 text-right font-demo text-sm font-bold whitespace-nowrap', 'text-demo-green' => $transaction['direction'] === 'in', 'text-demo-navy' => $transaction['direction'] === 'out', 'text-slate-400' => $transaction['direction'] === 'transfer'])>{{ $transaction['direction'] === 'in' ? '+' : '' }}{{ $peso($transaction['amount']) }}</td>
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
    {{-- Expense details drawer (filled from the clicked row) --}}
    <div data-expense-drawer class="fixed inset-0 z-[70] hidden justify-end bg-demo-navy/30 backdrop-blur-[2px]">
        <aside class="flex h-full w-full max-w-sm flex-col overflow-y-auto bg-white shadow-2xl">
            <div class="flex items-start justify-between gap-3 border-b border-slate-100 bg-slate-50/70 p-5">
                <div>
                    <div class="flex items-center gap-2">
                        <h3 data-drawer-ref class="font-demo text-lg font-extrabold text-demo-navy"></h3>
                        <span data-drawer-status></span>
                    </div>
                    <p data-drawer-description class="mt-0.5 text-xs text-slate-500"></p>
                </div>
                <button type="button" data-drawer-close aria-label="Close" class="flex size-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:text-slate-800"><span class="material-symbols-outlined text-[18px]">close</span></button>
            </div>
            <div class="flex-1 space-y-4 p-5 text-sm">
                <dl class="grid grid-cols-2 gap-3 rounded-xl border border-slate-200/60 bg-slate-50/60 p-4 text-xs">
                    <div><dt class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Date</dt><dd data-drawer-date class="mt-0.5 font-semibold text-slate-800"></dd></div>
                    <div><dt class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Budget category</dt><dd data-drawer-category class="mt-0.5 font-semibold text-slate-800"></dd></div>
                    <div><dt class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Vendor</dt><dd data-drawer-vendor class="mt-0.5 font-semibold text-slate-800"></dd></div>
                    <div><dt class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">Submitted by</dt><dd data-drawer-by class="mt-0.5 font-semibold text-slate-800"></dd></div>
                </dl>
                <div class="rounded-xl border border-slate-200/60 p-4">
                    <p class="mb-2 text-[10px] font-bold tracking-wider text-slate-400 uppercase">Line items</p>
                    <ul data-drawer-items class="space-y-2 text-xs"></ul>
                    <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-3">
                        <span class="text-xs font-semibold text-slate-600">Total amount</span>
                        <span data-drawer-total class="font-demo text-lg font-extrabold text-demo-navy"></span>
                    </div>
                </div>
            </div>
            <div class="flex gap-2 border-t border-slate-100 p-4">
                <button type="button" data-drawer-pay class="flex-1 rounded-xl bg-demo-green px-4 py-2.5 text-xs font-bold text-white hover:bg-emerald-800">Mark as paid</button>
                <button type="button" data-demo-toast-message="In the real app, Print gives you an expense voucher to file or sign." data-demo-toast-message-tl="Sa totoong app, may expense voucher na mapi-print mo para i-file o pirmahan." class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Print</button>
            </div>
        </aside>
    </div>

    <x-slot:guide>
        @include('demo.partials.guide', ['steps' => $tourSteps])
    </x-slot:guide>
    @endunless
</x-demo.layout>
