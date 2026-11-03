@php
    $clientPayments = [
        ['ref' => 'CP-0000002', 'label' => 'Progress Billing #1', 'date' => 'Aug 28, 2026', 'amount' => '₱1,020,000'],
        ['ref' => 'CP-0000001', 'label' => '30% Downpayment', 'date' => 'Jun 30, 2026', 'amount' => '₱3,060,000'],
    ];

    $payrollRuns = [
        ['ref' => 'PYR-0000023', 'period' => 'Oct 25–Oct 31', 'amount' => '₱58,400'],
        ['ref' => 'PYR-0000022', 'period' => 'Oct 18–Oct 24', 'amount' => '₱61,250'],
        ['ref' => 'PYR-0000021', 'period' => 'Oct 11–Oct 17', 'amount' => '₱57,800'],
        ['ref' => 'PYR-0000020', 'period' => 'Oct 04–Oct 10', 'amount' => '₱55,900'],
        ['ref' => 'PYR-0000019', 'period' => 'Sep 27–Oct 03', 'amount' => '₱54,300'],
    ];

    $expenses = [
        ['ref' => 'EXP-0000112', 'date' => 'Oct 30, 2026', 'amount' => '₱186,500', 'items' => 'Ready-mix Concrete 4,000 psi', 'icon' => 'local_shipping'],
        ['ref' => 'EXP-0000111', 'date' => 'Oct 29, 2026', 'amount' => '₱48,720', 'items' => 'Deformed Bars 12mm, Tie Wire #16', 'icon' => 'construction'],
        ['ref' => 'EXP-0000109', 'date' => 'Oct 27, 2026', 'amount' => '₱15,120', 'items' => 'Coco Lumber 2x2x10, Marine Plywood ½"', 'icon' => 'carpenter'],
        ['ref' => 'EXP-0000108', 'date' => 'Oct 24, 2026', 'amount' => '₱79,000', 'items' => 'Gravel, Sand', 'icon' => 'landscape'],
        ['ref' => 'EXP-0000105', 'date' => 'Oct 21, 2026', 'amount' => '₱12,600', 'items' => 'Electrical Pipe ½", Junction Box', 'icon' => 'electrical_services'],
    ];

    // status: done | active | upcoming
    $milestones = [
        ['date' => 'Jun 29', 'name' => 'Site Clearing & Layout', 'status' => 'done'],
        ['date' => 'Jul 06', 'name' => 'Footing Excavation', 'status' => 'done'],
        ['date' => 'Jul 13', 'name' => 'Gravel Bedding', 'status' => 'done'],
        ['date' => 'Jul 20', 'name' => 'Footing & Column Rebar', 'status' => 'done'],
        ['date' => 'Aug 01', 'name' => 'Footing & Pedestal Pouring', 'status' => 'done'],
        ['date' => 'Aug 06', 'name' => 'Foundation & Tie Beams', 'status' => 'done'],
        ['date' => 'Aug 11', 'name' => 'Septic Tank', 'status' => 'done'],
        ['date' => 'Aug 18', 'name' => 'Raised Ground-Floor Fill', 'status' => 'done'],
        ['date' => 'Aug 25', 'name' => 'Ground-Floor Slab', 'status' => 'done'],
        ['date' => 'Sep 08', 'name' => 'Ground-Floor Columns', 'status' => 'done'],
        ['date' => 'Sep 22', 'name' => 'Ground-Floor CHB Walls', 'status' => 'done'],
        ['date' => 'Oct 13', 'name' => 'Second-Floor Beams', 'status' => 'done'],
        ['date' => 'Nov 07', 'name' => 'Second-Floor Slab', 'status' => 'active'],
        ['date' => 'Mar 31', 'name' => 'Roofing, Finishes & Turnover', 'status' => 'upcoming'],
    ];

    $crew = [
        ['initials' => 'RS', 'name' => 'Ruben Santos (Foreman)', 'color' => 'bg-indigo-600'],
        ['initials' => 'PR', 'name' => 'Engr. Paolo Reyes (Site Engineer)', 'color' => 'bg-emerald-600'],
        ['initials' => 'MC', 'name' => 'Mateo Cruz (Mason)', 'color' => 'bg-amber-600'],
        ['initials' => 'DL', 'name' => 'Danilo Lim (Steelman)', 'color' => 'bg-rose-600'],
        ['initials' => 'AO', 'name' => 'Arnel Ocampo (Carpenter)', 'color' => 'bg-sky-600'],
        ['initials' => 'JT', 'name' => 'Jessie Tan (Electrician)', 'color' => 'bg-teal-600'],
    ];

    $tasks = [
        ['title' => 'Second-floor slab rebar inspection', 'category' => 'Structural', 'categoryClass' => 'border-indigo-100 bg-indigo-50 text-indigo-700', 'priority' => 'High', 'due' => 'Due Nov 3', 'assignee' => 'PR', 'assigneeColor' => 'bg-emerald-600'],
        ['title' => 'Order ready-mix concrete for slab pour', 'category' => 'Procurement', 'categoryClass' => 'border-purple-100 bg-purple-50 text-purple-700', 'priority' => 'High', 'due' => 'Due Nov 5', 'assignee' => 'RS', 'assigneeColor' => 'bg-indigo-600'],
        ['title' => 'Install second-floor shoring', 'category' => 'Formworks', 'categoryClass' => 'border-amber-100 bg-amber-50 text-amber-800', 'priority' => 'Medium', 'due' => 'Due Nov 6', 'assignee' => 'RS', 'assigneeColor' => 'bg-indigo-600'],
    ];

    $siteLogs = [
        ['date' => 'Oct 31, 2026', 'ago' => 'Today', 'type' => 'Daily Work Log', 'note' => 'Second-floor slab rebar tying, about 60% done.', 'by' => 'Ruben Santos', 'initials' => 'RS', 'color' => 'bg-indigo-600', 'photos' => ['site-01', 'site-02', 'site-03'], 'more' => 4],
        ['date' => 'Oct 30, 2026', 'ago' => '1 day ago', 'type' => 'Daily Work Log', 'note' => 'Slab formworks installed on grids A–C.', 'by' => 'Ruben Santos', 'initials' => 'RS', 'color' => 'bg-indigo-600', 'photos' => ['site-04', 'site-05', 'site-06'], 'more' => 2],
        ['date' => 'Oct 28, 2026', 'ago' => '3 days ago', 'type' => 'Progress Update', 'note' => 'Ground-floor CHB walls completed. Second-floor slab preparation started.', 'by' => 'Engr. Paolo Reyes', 'initials' => 'PR', 'color' => 'bg-emerald-600', 'photos' => ['site-07', 'site-08', 'site-09'], 'more' => 5],
        ['date' => 'Oct 26, 2026', 'ago' => '5 days ago', 'type' => 'Daily Work Log', 'note' => 'Scaffolding and shoring posts installed.', 'by' => 'Ruben Santos', 'initials' => 'RS', 'color' => 'bg-indigo-600', 'photos' => ['site-10', 'site-11', 'site-12'], 'more' => 1],
        ['date' => 'Oct 24, 2026', 'ago' => '1 week ago', 'type' => 'Daily Work Log', 'note' => 'Gravel and sand delivery received and checked.', 'by' => 'Ruben Santos', 'initials' => 'RS', 'color' => 'bg-indigo-600', 'photos' => ['site-13', 'site-14'], 'more' => 3],
    ];

    // Each step: English + Taglish copy, with an optional example drawn from the sample data above.
    $tourSteps = [
        [
            'target' => 'module-menu', 'overview' => true,
            'en' => ['title' => 'Everything ZionBuild can do', 'body' => 'This demo walks through ZionBuild one feature at a time: Command Center, Field Reports, AI Project Monitoring, Finances, Payroll and Client Billing. Click any tab to jump to it; the ones with a lock open after a quick sign-up.'],
            'tl' => ['title' => 'Lahat ng kayang gawin ng ZionBuild', 'body' => 'Isa-isa nating titingnan ang features ng ZionBuild: Command Center, Field Reports, AI Project Monitoring, Finances, Payroll at Client Billing. I-click lang ang tab para lumipat; yung may lock, bubukas pagkatapos ng mabilis na sign-up.'],
        ],
        [
            'target' => null,
            'en' => ['title' => 'Welcome to your Command Center', 'body' => "One screen for your whole project: money in and out, schedule, workforce and the latest site updates. You don't need to visit the site to know what's happening.", 'example' => "It's 7 AM and you're in the office. One look tells you 14 workers are on site and the slab rebar is 60% done."],
            'tl' => ['title' => 'Welcome sa Command Center mo', 'body' => 'Isang screen lang para sa buong project mo: perang pumasok at lumabas, schedule, workers at pinakabagong updates sa site. Hindi mo na kailangang pumunta sa site para malaman ang nangyayari.', 'example' => '7 AM, nasa office ka. Isang tingin lang, alam mo nang 14 ang workers sa site at 60% na ang rebar ng slab.'],
        ],
        [
            'target' => 'project-switcher',
            'en' => ['title' => 'Switch projects', 'body' => 'Managing several sites? Pick a project here and the whole dashboard follows it.', 'example' => 'Jump from Ong Residential to your other site in Cavite in one click.'],
            'tl' => ['title' => 'Lipat ng project', 'body' => 'Marami kang site? Piliin dito ang project at susunod ang buong dashboard.', 'example' => 'Isang click lang, lipat ka na mula Ong Residential papunta sa project mo sa Cavite.'],
        ],
        [
            'target' => 'financial-status',
            'en' => ['title' => 'Contract, budget & earnings', 'body' => 'See your contract price, how much of it has been spent, and your projected earnings at a glance, so you know early if a project is getting tight.', 'example' => 'Contract ₱10.2M, budget ₱7.14M, so you expect to earn ₱3.06M, a 30% margin. Only 33% of the contract has been spent so far.'],
            'tl' => ['title' => 'Kontrata, budget at kita', 'body' => 'Kita mo agad ang contract price, magkano na ang nagastos, at ang inaasahang kita, para alam mo nang maaga kung humihigpit ang project.', 'example' => '₱10.2M ang kontrata at ₱7.14M ang budget, kaya ₱3.06M ang inaasahang kita, 30% margin. 33% pa lang ng kontrata ang nagagastos.'],
        ],
        [
            'target' => 'net-cash',
            'en' => ['title' => 'Net Cash Position', 'body' => 'Money collected from the client minus money spent. Positive means the project is funding itself.', 'example' => "The client has paid ₱4,080,000 and you've spent ₱3,345,050, so the project is ₱734,950 ahead."],
            'tl' => ['title' => 'Net Cash Position', 'body' => 'Perang nakolekta sa client bawas lahat ng nagastos. Kapag positive, kaya ng project ang sarili niyang gastos.', 'example' => '₱4,080,000 na ang naibayad ng client at ₱3,345,050 ang nagastos, kaya lamang ng ₱734,950 ang project.'],
        ],
        [
            'target' => 'money-cards',
            'en' => ['title' => 'Payments, payroll & expenses', 'body' => 'The latest client payments, payroll runs and paid expenses, each with a running total. Every peso has a record.', 'example' => 'Wondering where ₱186,500 went on Oct 30? It\'s EXP-0000112: ready-mix concrete for the slab.'],
            'tl' => ['title' => 'Bayad, sahod at gastos', 'body' => 'Ang pinakabagong bayad ng client, payroll at bayad na gastos, may running total bawat isa. Bawat piso, may record.', 'example' => 'Saan napunta ang ₱186,500 noong Oct 30? EXP-0000112 yan: ready-mix concrete para sa slab.'],
        ],
        [
            'target' => 'timeline',
            'en' => ['title' => 'Timeline & Cash Burn', 'body' => "Milestones, days remaining, daily burn and completion %. Are we still on schedule? You'll see it right away.", 'example' => '42% complete with about 45% of the schedule used, so the project is on track.'],
            'tl' => ['title' => 'Timeline at Cash Burn', 'body' => 'Milestones, ilang araw pa, daily na gastos at completion %. On schedule pa ba tayo? Makikita mo agad.', 'example' => '42% na ang tapos at mga 45% ng schedule ang nagamit, kaya on track ang project.'],
        ],
        [
            'target' => 'workforce',
            'en' => ['title' => "Who's on site today", 'body' => 'Workers who timed in today, skilled vs laborers, straight from attendance.', 'example' => '14 timed in today: 5 skilled workers and 9 laborers.'],
            'tl' => ['title' => 'Sino ang nasa site today', 'body' => 'Mga workers na nag-time in ngayong araw, skilled at laborers, galing mismo sa attendance.', 'example' => '14 ang nag-time in today: 5 skilled at 9 laborers.'],
        ],
        [
            'target' => 'site-progress',
            'en' => ['title' => 'Latest Site Progress', 'body' => 'Daily work logs and photos from the field. See actual progress right on your phone.', 'example' => 'Today\'s log from Ruben Santos: "Second-floor slab rebar tying, about 60% done," with 7 photos.'],
            'tl' => ['title' => 'Pinakabagong progress sa site', 'body' => 'Daily work logs at photos mula sa site. Actual progress, kita sa phone mo.', 'example' => 'Log ni Ruben Santos today: "Second-floor slab rebar tying, about 60% done," may 7 photos.'],
        ],
        [
            'target' => null, 'final' => true,
            'next' => ['label' => ['en' => 'Field Reports', 'tl' => 'Field Reports'], 'url' => route('demo.field-reports')],
            'en' => ['title' => "That's the Command Center", 'body' => 'Next: Field Reports, where your team sends photos and work logs straight from the site. Or book a demo to see it with your own projects.'],
            'tl' => ['title' => 'Yan ang Command Center', 'body' => 'Susunod: Field Reports, kung saan nagpapadala ang team mo ng photos at work logs mula mismo sa site. O mag-book ng demo para makita ito gamit ang sarili mong projects.'],
        ],
    ];
@endphp

<x-demo.layout title="Command Center" active="command-center">
    <div class="space-y-6">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <h1 class="font-demo text-[28px] font-extrabold tracking-tight text-demo-navy">Command Center</h1>
            <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-600">
                <span class="material-symbols-outlined text-[15px]">calendar_today</span>
                Today: Sat, Oct 31, 2026
            </span>
        </div>

        {{-- Row 1: Financial status + Net cash --}}
        <div class="grid gap-6 lg:grid-cols-12">
            <section data-tour="financial-status" class="relative overflow-hidden rounded-2xl border border-white/60 bg-linear-to-br from-[#fce7f3] via-[#eef2ff] to-[#c7d2fe] p-5 shadow-sm sm:p-6 lg:col-span-8">
                <x-demo.info class="absolute top-5 right-5" />

                <div class="flex flex-col gap-4 pr-8 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="font-demo text-2xl leading-tight font-extrabold tracking-tight text-demo-navy sm:text-[30px]">Ong Residential</h2>
                            <span class="flex size-7 items-center justify-center rounded-full bg-white/80 text-slate-600 shadow-sm">
                                <span class="material-symbols-outlined text-[17px]">visibility</span>
                            </span>
                        </div>
                        <div class="mt-1 flex flex-wrap items-center gap-2 text-xs font-medium text-slate-600">
                            <span>Jun 29, 2026 — Mar 31, 2027</span>
                            <span class="rounded bg-white/60 px-2 py-0.5 text-[11px] font-semibold text-slate-700">Antipolo, Rizal</span>
                        </div>
                    </div>
                    <div class="sm:text-right">
                        <span class="block text-[11px] font-bold tracking-wider text-slate-600 uppercase">Contract Price</span>
                        <span class="font-demo text-[28px] font-extrabold tracking-tight text-demo-navy sm:text-[32px]">₱10,200,000</span>
                    </div>
                </div>

                <div class="mt-6 grid gap-4 border-t border-slate-300/40 pt-5 sm:grid-cols-2 sm:gap-6">
                    <div class="rounded-xl border border-white/50 bg-white/60 p-4">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="text-[11px] font-bold tracking-wider text-slate-600 uppercase">Estimated Project Cost</span>
                            <span class="font-demo text-lg font-bold text-demo-navy">₱7,140,000</span>
                        </div>
                        <div class="mt-3 h-2.5 overflow-hidden rounded-full bg-slate-200/80">
                            <div class="h-full w-[33%] rounded-full bg-demo-orange"></div>
                        </div>
                        <p class="mt-2 text-xs text-slate-600 italic">Utilized ₱3,345,050 (33%) of contract price</p>
                    </div>
                    <div class="rounded-xl border border-white/50 bg-white/60 p-4">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="text-[11px] font-bold tracking-wider text-slate-600 uppercase">Projected Earnings</span>
                            <span class="font-demo text-lg font-bold text-demo-green">₱3,060,000</span>
                        </div>
                        <div class="mt-3 h-2.5 overflow-hidden rounded-full bg-slate-200/80">
                            <div class="h-full w-[24%] rounded-full bg-demo-green"></div>
                        </div>
                        <p class="mt-2 text-xs text-slate-600 italic">Realized ₱734,950 (24%) to date</p>
                    </div>
                </div>
            </section>

            <section data-tour="net-cash" class="relative flex flex-col justify-between overflow-hidden rounded-2xl bg-demo-navy p-5 text-white shadow-sm sm:p-6 lg:col-span-4">
                <x-demo.info dark class="absolute top-5 right-5" />
                <span class="material-symbols-outlined pointer-events-none absolute -right-6 -bottom-8 text-[180px] text-white/[0.04]">architecture</span>

                <div>
                    <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Net Cash Position</span>
                    <div class="mt-1 font-demo text-[38px] font-extrabold tracking-tight">₱734,950</div>
                    <div class="mt-1 flex items-center gap-1.5 text-xs text-demo-mint">
                        <span class="material-symbols-outlined text-[15px]">trending_up</span>
                        More collected than spent
                    </div>
                </div>

                <dl class="relative mt-6 space-y-3 border-t border-slate-700/60 pt-4 text-xs">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                        <dt class="text-slate-300">Total Inflow Collected</dt>
                        <dd class="font-demo text-sm font-bold text-demo-mint">+ ₱4,080,000</dd>
                    </div>
                    <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                        <dt class="text-slate-300">Total Outflows</dt>
                        <dd class="font-demo text-sm font-bold text-demo-orange">− ₱3,345,050</dd>
                    </div>
                    <div class="flex items-center justify-between pt-1">
                        <dt class="text-slate-300">Company Cash on Hand (Main Bank)</dt>
                        <dd class="font-demo text-base font-extrabold">₱2,486,730</dd>
                    </div>
                </dl>
            </section>
        </div>

        {{-- Row 2: Client payments, payroll, expenses --}}
        <div data-tour="money-cards" class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            <section class="flex flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="flex h-12 items-center justify-between bg-demo-navy px-5 text-white">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px] text-demo-mint">payments</span>
                        <span class="text-sm font-semibold">Client Payments</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="rounded-full bg-demo-mint/20 px-2.5 py-0.5 text-[10px] font-extrabold tracking-wide text-demo-mint uppercase">Inflow</span>
                        <x-demo.info dark />
                    </div>
                </div>
                <div class="flex flex-1 flex-col p-5">
                    <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Total Collected</span>
                    <div class="mt-0.5 font-demo text-2xl font-extrabold text-demo-green">₱4,080,000</div>
                    <div class="mt-4 space-y-3">
                        @foreach ($clientPayments as $payment)
                            <div class="flex items-start gap-3 rounded-xl border border-emerald-100 bg-emerald-50/40 p-3">
                                <span class="material-symbols-outlined mt-0.5 shrink-0 text-[20px] text-demo-green">check_circle</span>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-semibold text-slate-800">{{ $payment['ref'] }}</span>
                                        <span class="font-demo text-xs font-bold text-demo-green">{{ $payment['amount'] }}</span>
                                    </div>
                                    <div class="mt-0.5 text-[11px] font-medium text-slate-600">{{ $payment['label'] }}</div>
                                    <div class="mt-0.5 text-[10px] text-slate-400">{{ $payment['date'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-auto border-t border-slate-100 pt-3 text-[11px] text-slate-400">40% of the contract price collected so far</p>
                </div>
            </section>

            <section class="flex flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="flex h-12 items-center justify-between bg-demo-navy px-5 text-white">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px] text-demo-orange">engineering</span>
                        <span class="text-sm font-semibold">Workers Payroll</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="rounded-full bg-demo-orange/20 px-2.5 py-0.5 text-[10px] font-extrabold tracking-wide text-demo-orange uppercase">Outflow</span>
                        <x-demo.info dark />
                    </div>
                </div>
                <div class="flex flex-1 flex-col p-5">
                    <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Total Gross Payroll</span>
                    <div class="mt-0.5 font-demo text-2xl font-extrabold text-demo-navy">₱1,148,600</div>
                    <div class="mt-4 space-y-2">
                        @foreach ($payrollRuns as $run)
                            <div class="flex items-center justify-between rounded-lg bg-slate-50 px-2.5 py-2">
                                <div class="flex items-center gap-2">
                                    <span class="flex size-7 items-center justify-center rounded bg-amber-100 text-amber-800">
                                        <span class="material-symbols-outlined text-[15px]">receipt</span>
                                    </span>
                                    <div>
                                        <div class="text-xs font-semibold text-slate-800">{{ $run['ref'] }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $run['period'] }}</div>
                                    </div>
                                </div>
                                <span class="font-demo text-xs font-bold text-slate-800">{{ $run['amount'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-auto border-t border-slate-100 pt-3 text-[11px] text-slate-400">Weekly payroll runs · latest 5 shown</p>
                </div>
            </section>

            <section class="flex flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm md:col-span-2 xl:col-span-1">
                <div class="flex h-12 items-center justify-between bg-demo-navy px-5 text-white">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px] text-demo-orange">shopping_cart</span>
                        <span class="text-sm font-semibold">Expenses</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="rounded-full bg-demo-orange/20 px-2.5 py-0.5 text-[10px] font-extrabold tracking-wide text-demo-orange uppercase">Outflow</span>
                        <x-demo.info dark />
                    </div>
                </div>
                <div class="flex flex-1 flex-col p-5">
                    <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase">Total Expenses</span>
                    <div class="mt-0.5 font-demo text-2xl font-extrabold text-demo-navy">₱2,196,450</div>
                    <div class="mt-4 space-y-2">
                        @foreach ($expenses as $expense)
                            <div class="rounded-lg bg-slate-50 p-2 text-xs">
                                <div class="flex items-center justify-between gap-2">
                                    <div>
                                        <span class="font-semibold text-slate-800">{{ $expense['ref'] }}</span>
                                        <span class="text-slate-400">· {{ $expense['date'] }}</span>
                                    </div>
                                    <span class="font-demo font-bold text-slate-900">{{ $expense['amount'] }}</span>
                                </div>
                                <div class="mt-1 flex items-center gap-1 text-[11px] font-medium text-demo-blue">
                                    <span class="material-symbols-outlined text-[13px]">{{ $expense['icon'] }}</span>
                                    {{ $expense['items'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-auto border-t border-slate-100 pt-3 text-[11px] text-slate-400">Paid expenses · latest 5 shown</p>
                </div>
            </section>
        </div>

        {{-- Row 3: Timeline & cash burn --}}
        <section data-tour="timeline" class="relative rounded-2xl border border-l-[6px] border-slate-200/70 border-l-demo-navy bg-demo-track p-5 shadow-sm sm:p-6">
            <x-demo.info class="absolute top-5 right-5" />

            <div class="flex flex-col gap-4 pr-8 md:flex-row md:items-start md:justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-demo text-xl font-extrabold text-demo-navy">Timeline &amp; Cash Burn</h3>
                        <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-bold text-demo-green">On track</span>
                    </div>
                    <p class="mt-0.5 text-xs font-medium text-slate-500">151 days remaining in project cycle</p>
                </div>
                <div class="flex gap-3">
                    <div class="rounded-xl border border-slate-100 bg-white px-4 py-2.5 shadow-sm">
                        <span class="block text-[10px] font-bold tracking-wider text-slate-400 uppercase">Avg Daily Burn</span>
                        <span class="font-demo text-base font-extrabold text-demo-navy">₱26,976</span>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-white px-4 py-2.5 shadow-sm">
                        <span class="block text-[10px] font-bold tracking-wider text-slate-400 uppercase">Project Completion</span>
                        <span class="font-demo text-base font-extrabold text-demo-green">42%</span>
                    </div>
                </div>
            </div>

            <div class="-mx-2 mt-6 overflow-x-auto px-2 pb-2">
                <ol class="relative grid min-w-[980px] grid-cols-14">
                    {{-- Track runs between the first and last dot centres; filled up to the active milestone --}}
                    <li aria-hidden="true" class="absolute top-[62px] right-[calc(100%/28)] left-[calc(100%/28)] h-1 -translate-y-1/2 rounded-full bg-slate-200">
                        <div class="h-full w-[92.3%] rounded-full bg-demo-navy"></div>
                    </li>

                    @foreach ($milestones as $milestone)
                        @php($isAbove = $loop->odd)
                        <li class="relative flex flex-col items-center text-center" title="Milestone {{ $loop->iteration }} — {{ $milestone['name'] }}">
                            <div class="flex h-12 items-end pb-2">
                                @if ($isAbove)
                                    @include('demo.partials.milestone-label', ['milestone' => $milestone, 'number' => $loop->iteration])
                                @endif
                            </div>
                            <div class="flex h-7 items-center">
                                @if ($milestone['status'] === 'done')
                                    <span class="z-10 flex size-6 items-center justify-center rounded-full bg-demo-navy text-white ring-4 ring-demo-track">
                                        <span class="material-symbols-outlined text-[14px]">check</span>
                                    </span>
                                @elseif ($milestone['status'] === 'active')
                                    <span class="relative z-10 flex size-7 items-center justify-center rounded-full bg-demo-blue ring-4 ring-blue-100">
                                        <span class="absolute inset-0 animate-ping rounded-full bg-demo-blue/50"></span>
                                        <span class="relative size-2.5 rounded-full bg-white"></span>
                                    </span>
                                @else
                                    <span class="z-10 flex size-6 items-center justify-center rounded-full border-2 border-slate-300 bg-white ring-4 ring-demo-track">
                                        <span class="size-1.5 rounded-full bg-slate-300"></span>
                                    </span>
                                @endif
                            </div>
                            <div class="flex h-12 items-start pt-2">
                                @unless ($isAbove)
                                    @include('demo.partials.milestone-label', ['milestone' => $milestone, 'number' => $loop->iteration])
                                @endunless
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
            <p class="mt-1 text-[11px] text-slate-400 md:hidden">Swipe to see all milestones →</p>
        </section>

        {{-- Row 4: Workforce + tasks, site progress --}}
        <div class="grid items-start gap-6 lg:grid-cols-12">
            <div class="space-y-6 lg:col-span-5">
                <section data-tour="workforce" class="relative overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                    <div class="flex h-12 items-center justify-between bg-demo-navy px-5 text-white">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px] text-amber-400">badge</span>
                            <span class="text-sm font-semibold">Total Workforce</span>
                        </div>
                        <x-demo.info dark />
                    </div>
                    <div class="p-5 sm:p-6">
                        <div class="flex flex-wrap items-baseline justify-between gap-3">
                            <div class="flex items-baseline gap-3">
                                <span class="font-demo text-[44px] leading-none font-extrabold text-demo-navy">14</span>
                                <span class="text-sm font-semibold text-slate-600">Workers on Site Today</span>
                            </div>
                            <div class="flex gap-1.5">
                                <span class="rounded-full border border-blue-100 bg-blue-50 px-2.5 py-1 text-[11px] font-bold text-demo-blue">5 Skilled</span>
                                <span class="rounded-full border border-amber-100 bg-amber-50 px-2.5 py-1 text-[11px] font-bold text-amber-800">9 Laborers</span>
                            </div>
                        </div>
                        <div class="mt-5 flex items-center -space-x-2 border-t border-slate-100 pt-4">
                            @foreach ($crew as $member)
                                <span title="{{ $member['name'] }}" class="flex size-9 items-center justify-center rounded-full {{ $member['color'] }} text-xs font-bold text-white ring-2 ring-white">{{ $member['initials'] }}</span>
                            @endforeach
                            <span title="8 more workers" class="flex size-9 items-center justify-center rounded-full bg-slate-200 text-xs font-extrabold text-slate-700 ring-2 ring-white">+8</span>
                        </div>
                    </div>
                </section>

                <section class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm sm:p-6">
                    <h3 class="font-demo text-base font-extrabold text-demo-navy">Active Tasks</h3>
                    <p class="text-xs text-slate-500">In progress · soonest due first</p>
                    <div class="mt-4 space-y-3">
                        @foreach ($tasks as $task)
                            <div class="rounded-xl border border-slate-200/80 bg-slate-50/60 p-3.5">
                                <div class="flex items-start justify-between gap-2">
                                    <h4 class="text-xs leading-snug font-semibold text-slate-800">{{ $task['title'] }}</h4>
                                    <span class="flex size-6 shrink-0 items-center justify-center rounded-full {{ $task['assigneeColor'] }} text-[10px] font-bold text-white">{{ $task['assignee'] }}</span>
                                </div>
                                <div class="mt-2.5 flex flex-wrap items-center gap-2">
                                    <span class="rounded border px-2 py-0.5 text-[10px] font-semibold {{ $task['categoryClass'] }}">{{ $task['category'] }}</span>
                                    <span @class([
                                        'rounded border px-2 py-0.5 text-[10px] font-bold',
                                        'border-rose-100 bg-rose-50 text-rose-700' => $task['priority'] === 'High',
                                        'border-amber-100 bg-amber-50 text-amber-700' => $task['priority'] !== 'High',
                                    ])>{{ $task['priority'] }}</span>
                                    <span class="ml-auto flex items-center gap-1 text-[11px] font-medium text-slate-500">
                                        <span class="material-symbols-outlined text-[13px]">alarm</span>
                                        {{ $task['due'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            <section data-tour="site-progress" class="relative rounded-2xl border border-slate-100 bg-white p-5 shadow-sm sm:p-6 lg:col-span-7">
                <x-demo.info class="absolute top-5 right-5" />
                <h3 class="font-demo text-lg font-extrabold text-demo-navy">Latest Site Progress</h3>
                <p class="mb-5 text-xs text-slate-500">Daily work logs and progress updates from the field</p>

                <ol class="relative ml-2 space-y-6 border-l-2 border-slate-200">
                    @foreach ($siteLogs as $log)
                        <li class="relative pl-5 sm:pl-6">
                            <span @class([
                                'absolute top-1 rounded-full border-2 border-white',
                                '-left-[9px] size-4 bg-demo-green' => $loop->first,
                                '-left-[7px] size-3 bg-slate-400' => ! $loop->first,
                            ])></span>
                            <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-[11px] font-bold tracking-wider text-slate-500 uppercase">
                                <span>{{ $log['date'] }}</span>
                                <span>•</span>
                                @if ($loop->first)
                                    <span class="rounded bg-demo-mint/30 px-2 py-0.5 font-extrabold text-demo-green">{{ $log['ago'] }}</span>
                                @else
                                    <span>{{ $log['ago'] }}</span>
                                @endif
                                <span>•</span>
                                <span @class(['font-extrabold text-indigo-600' => $log['type'] === 'Progress Update'])>{{ $log['type'] }}</span>
                            </div>

                            <div @class(['mt-2 rounded-xl border bg-slate-50/50 p-3', 'border-demo-mint/60' => $loop->first, 'border-slate-200/80' => ! $loop->first])>
                                <div class="grid grid-cols-12 gap-2 sm:gap-3">
                                    <div @class(['group col-span-7 overflow-hidden rounded-lg bg-slate-200', 'h-36' => $loop->first, 'h-28' => ! $loop->first])>
                                        <img src="{{ asset('images/demo/'.$log['photos'][0].'.jpg') }}" alt="{{ $log['note'] }}" loading="lazy" class="size-full object-cover transition duration-300 group-hover:scale-105">
                                    </div>
                                    <div @class(['col-span-5 grid grid-rows-3 gap-1', 'h-36' => $loop->first, 'h-28' => ! $loop->first])>
                                        @foreach (array_slice($log['photos'], 1, 2) as $photo)
                                            <img src="{{ asset('images/demo/'.$photo.'.jpg') }}" alt="" loading="lazy" class="size-full rounded object-cover">
                                        @endforeach
                                        @for ($slot = count(array_slice($log['photos'], 1, 2)); $slot < 2; $slot++)
                                            <span class="rounded bg-slate-200"></span>
                                        @endfor
                                        <span class="flex items-center justify-center rounded bg-demo-navy text-[10px] font-bold text-white">+{{ $log['more'] }} photos</span>
                                    </div>
                                </div>
                                <div class="mt-2.5 flex flex-col gap-2 border-t border-slate-200/60 pt-2.5 sm:flex-row sm:items-center sm:justify-between">
                                    <p class="text-xs font-semibold text-slate-800">{{ $log['note'] }}</p>
                                    <div class="flex shrink-0 items-center gap-1.5">
                                        <span class="flex size-5 items-center justify-center rounded-full {{ $log['color'] }} text-[9px] font-bold text-white">{{ $log['initials'] }}</span>
                                        <span class="text-[11px] font-medium text-slate-600">{{ $log['by'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </section>
        </div>
    </div>

    <x-slot:guide>
        @include('demo.partials.guide', ['steps' => $tourSteps])
    </x-slot:guide>
</x-demo.layout>
