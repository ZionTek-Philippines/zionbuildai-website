@php
    // Weighted phases: actual = Σ weight × actual% = 42.0%, planned today (week 18) = 40.4%.
    // accomplished = the peso value in the Accomplishment Report (totals ₱4,284,000 = 42% of the contract).
    $phases = [
        ['name' => 'Site Works & Earthworks', 'weight' => 4, 'planned' => 100, 'actual' => 100, 'accomplished' => 408000],
        ['name' => 'Foundation & Footings', 'weight' => 10, 'planned' => 100, 'actual' => 100, 'accomplished' => 1020000],
        ['name' => 'Ground-Floor Structure', 'weight' => 12, 'planned' => 100, 'actual' => 100, 'accomplished' => 1224000],
        ['name' => 'Ground-Floor Masonry', 'weight' => 8, 'planned' => 100, 'actual' => 100, 'accomplished' => 816000],
        ['name' => 'Second-Floor Structure', 'weight' => 14, 'planned' => 46, 'actual' => 57.1, 'accomplished' => 816000],
        ['name' => 'Second-Floor Masonry', 'weight' => 8, 'planned' => 0, 'actual' => 0, 'accomplished' => 0],
        ['name' => 'Roofing', 'weight' => 10, 'planned' => 0, 'actual' => 0, 'accomplished' => 0],
        ['name' => 'Plumbing & Electrical', 'weight' => 12, 'planned' => 0, 'actual' => 0, 'accomplished' => 0],
        ['name' => 'Finishes', 'weight' => 16, 'planned' => 0, 'actual' => 0, 'accomplished' => 0],
        ['name' => 'Turnover & Clean-up', 'weight' => 6, 'planned' => 0, 'actual' => 0, 'accomplished' => 0],
    ];
    $contractPrice = 10_200_000;
    $romanNumerals = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X'];
    $peso = fn (float $amount): string => '₱'.number_format($amount);

    // S-Curve: planned follows a smooth S fitted to 40.4% at week 18 and 100% at week 40;
    // actual tracks it and pulls ahead to 42.0% by today (week 18).
    $plannedAt = function (int $week): float {
        $t = ($week / 40) ** 1.0406;

        return 100 * (3 * $t ** 2 - 2 * $t ** 3);
    };
    $chartX = fn (int $week): float => round(60 + $week / 40 * 920, 1);
    $chartY = fn (float $percent): float => round(280 - $percent * 2.5, 1);
    $plannedPoints = collect(range(0, 40))->map(fn ($week) => $chartX($week).','.$chartY($plannedAt($week)))->implode(' ');
    $actualPoints = collect(range(0, 18))->map(fn ($week) => $chartX($week).','.$chartY($plannedAt($week) * (1 + 0.0396 * $week / 18)))->implode(' ');

    $analysisQueue = [
        ['note' => 'Second-floor slab rebar tying, about 60% done.', 'by' => 'Ruben Santos', 'date' => 'Oct 31, 2026', 'photos' => 7, 'photo' => 'fr-01', 'status' => 'review'],
        ['note' => 'Slab formworks installed on grids A–C.', 'by' => 'Ruben Santos', 'date' => 'Oct 30, 2026', 'photos' => 5, 'photo' => 'fr-04', 'status' => 'accepted'],
        ['note' => 'Ground-floor CHB walls completed.', 'by' => 'Engr. Paolo Reyes', 'date' => 'Oct 28, 2026', 'photos' => 8, 'photo' => 'fr-07', 'status' => 'accepted'],
        ['note' => 'Nag-tali ng rebar sa grid B–C, 2nd floor slab.', 'by' => 'Danilo Lim', 'date' => 'Oct 31, 2026', 'photos' => 3, 'photo' => 'fr-02', 'status' => 'analyzing'],
    ];

    $milestoneMatches = [
        ['rank' => 1, 'item' => 'Slab reinforcement', 'milestone' => 'M13 Second-Floor Slab', 'current' => 45, 'evidence' => 'Strong', 'range' => '55–65%', 'recommended' => 60],
        ['rank' => 2, 'item' => 'Slab formworks', 'milestone' => 'M13 Second-Floor Slab', 'current' => 90, 'evidence' => 'Supporting', 'range' => '90–100%', 'recommended' => 95],
        ['rank' => 3, 'item' => 'Beam reinforcement', 'milestone' => 'M12 Second-Floor Beams', 'current' => 100, 'evidence' => 'Weak', 'range' => null, 'recommended' => null],
    ];

    $tourSteps = [
        [
            'target' => null,
            'en' => ['title' => 'AI Project Monitoring', 'body' => 'Know exactly where the project stands: planned vs actual progress, the accomplishment report you bill from, and AI that reads your site photos.'],
            'tl' => ['title' => 'AI Project Monitoring', 'body' => 'Alamin kung nasaan na talaga ang project: planned vs actual na progress, ang accomplishment report na basehan ng billing, at AI na nagbabasa ng site photos mo.'],
        ],
        [
            'target' => 'progress-kpis', 'tab' => 'progress',
            'en' => ['title' => 'Ahead, on time or behind?', 'body' => 'Planned target for today, actual progress and the gap between them, in one row.', 'example' => 'The plan says 40.4% by today and the site is at 42.0%, so you are 1.6% ahead.'],
            'tl' => ['title' => 'Advance, on time o delayed?', 'body' => 'Ang planned target ngayong araw, ang actual na progress, at ang pagitan nila, sa iisang row.', 'example' => '40.4% ang plano ngayong araw at 42.0% na ang site, kaya advance ka ng 1.6%.'],
        ],
        [
            'target' => 's-curve', 'tab' => 'progress',
            'en' => ['title' => 'The S-Curve', 'body' => 'Planned progress (dashed) vs actual (blue), week by week. When blue is above the dashed line, work is ahead of plan.', 'example' => 'At week 18 the blue line sits just above the plan: 42.0% vs 40.4%.'],
            'tl' => ['title' => 'Ang S-Curve', 'body' => 'Planned na progress (dashed) vs actual (blue), linggo-linggo. Kapag nasa itaas ang blue, advance ang trabaho.', 'example' => 'Sa week 18, bahagyang nasa itaas ng plano ang blue line: 42.0% vs 40.4%.'],
        ],
        [
            'target' => 'phase-breakdown', 'tab' => 'progress',
            'en' => ['title' => 'Phase by phase', 'body' => 'Each phase has a weight. Its progress, multiplied by its weight, adds up to the overall percentage.', 'example' => 'Second-Floor Structure is 57% done against a 46% plan, which is what puts the whole project ahead.'],
            'tl' => ['title' => 'Bawat phase', 'body' => 'May weight ang bawat phase. Ang progress nito, times ang weight, ang bumubuo sa overall na porsyento.', 'example' => '57% na ang Second-Floor Structure laban sa 46% na plano, kaya advance ang buong project.'],
        ],
        [
            'target' => 'accomplishment', 'tab' => 'report',
            'en' => ['title' => 'Accomplishment Report', 'body' => 'Progress turned into pesos: each work item\'s accomplished value, ready to support your next progress billing.', 'example' => '₱4,284,000 of the ₱10,200,000 contract is accomplished, a clear basis for Progress Billing #2.'],
            'tl' => ['title' => 'Accomplishment Report', 'body' => 'Ang progress, ginawang piso: ang accomplished value ng bawat work item, handang suportahan ang susunod mong progress billing.', 'example' => '₱4,284,000 na sa ₱10,200,000 na kontrata ang accomplished, malinaw na basehan para sa Progress Billing #2.'],
        ],
        [
            'target' => 'ai-queue', 'tab' => 'photos',
            'en' => ['title' => 'AI Photo Analysis', 'body' => 'Any work log with photos can be sent to AI. It reads the photos and reports what it sees, then waits for your review.', 'example' => 'Ruben\'s 7 rebar photos from today are analyzed and waiting for you.'],
            'tl' => ['title' => 'AI Photo Analysis', 'body' => 'Puwedeng ipasa sa AI ang kahit anong work log na may photos. Babasahin nito ang photos, sasabihin kung ano ang nakita, at hihintayin ang review mo.', 'example' => 'Na-analyze na ang 7 rebar photos ni Ruben today at hinihintay ka na.'],
        ],
        [
            'target' => 'ai-summary', 'tab' => 'photos',
            'en' => ['title' => 'What the AI saw', 'body' => 'A plain summary, plus confidence, image quality and how much of the work the photos cover. It also lists what it cannot verify.', 'example' => 'It saw rebar tied on grids A–C, and warns that grids D–E are not in the photos.'],
            'tl' => ['title' => 'Ano ang nakita ng AI', 'body' => 'Simpleng summary, kasama ang confidence, image quality at kung gaano kalaki ng trabaho ang kita sa photos. Sinasabi rin nito ang hindi nito ma-verify.', 'example' => 'Nakita nitong tapos ang rebar sa grids A–C, at nagbabala na wala sa photos ang grids D–E.'],
        ],
        [
            'target' => 'ai-matches', 'tab' => 'photos',
            'en' => ['title' => 'You approve every change', 'body' => 'AI ranks the milestone items the photos match and suggests a progress range. Nothing changes until you accept. Try it: click "Accept match".', 'example' => 'Accept #1 and Slab reinforcement moves from 45% to 60%.'],
            'tl' => ['title' => 'Ikaw ang nag-a-approve', 'body' => 'Nira-rank ng AI ang mga milestone item na tugma sa photos at nagsa-suggest ng progress range. Walang magbabago hangga\'t hindi mo tinatanggap. Subukan: i-click ang "Accept match".', 'example' => 'I-accept ang #1 at magiging 60% ang Slab reinforcement mula 45%.'],
        ],
        [
            'target' => 'ai-insights', 'tab' => 'insights',
            'en' => ['title' => 'More AI on the way', 'body' => 'More AI features are in beta. Ask us about them when you book your demo.'],
            'tl' => ['title' => 'May paparating pang AI', 'body' => 'May iba pang AI features na naka-beta. Itanong sa amin kapag nag-book ka ng demo.'],
        ],
        [
            'target' => null, 'final' => true,
            'next' => ['label' => ['en' => 'Finances', 'tl' => 'Finances'], 'url' => route('demo.finances')],
            'en' => ['title' => "That's AI Project Monitoring", 'body' => 'Planned vs actual, billing-ready accomplishment, and AI that reads your site photos. Want to see it with your own projects?'],
            'tl' => ['title' => 'Yan ang AI Project Monitoring', 'body' => 'Planned vs actual, accomplishment na handa sa billing, at AI na nagbabasa ng site photos mo. Gusto mo bang makita ito gamit ang sarili mong projects?'],
        ],
    ];

    $tabs = [
        ['key' => 'progress', 'label' => 'Progress & S-Curve'],
        ['key' => 'report', 'label' => 'Accomplishment Report'],
        ['key' => 'photos', 'label' => 'AI Photo Analysis', 'chip' => 'NEW'],
        ['key' => 'insights', 'label' => 'AI Insights', 'chip' => 'BETA'],
    ];
@endphp

<x-demo.layout title="AI Project Monitoring" active="ai-project-monitoring" active-icon="bar_chart">
    <div class="space-y-6">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="font-demo text-[28px] font-extrabold tracking-tight text-demo-navy">AI Project Monitoring</h1>
                <p class="text-sm text-slate-500">Planned vs actual progress, accomplishment reports, and AI that reads your site photos.</p>
            </div>
            <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-600">
                <span class="material-symbols-outlined text-[15px]">calendar_today</span>
                As of Sat, Oct 31, 2026
            </span>
        </div>

        <div data-tour="monitoring-tabs" role="tablist" class="-mx-1 flex gap-1 overflow-x-auto rounded-full border border-slate-200/60 bg-white/80 p-1.5 shadow-sm [scrollbar-width:none] sm:mx-0 sm:self-start lg:inline-flex">
            @foreach ($tabs as $tab)
                <button type="button" role="tab" data-demo-tab="{{ $tab['key'] }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-4 py-2 text-xs font-medium text-slate-600 transition hover:bg-slate-100 aria-selected:bg-demo-navy aria-selected:font-bold aria-selected:text-white">
                    @if (str_starts_with($tab['label'], 'AI'))
                        <span class="material-symbols-outlined text-[15px] text-indigo-500">auto_awesome</span>
                    @endif
                    {{ $tab['label'] }}
                    @isset($tab['chip'])
                        <span @class([
                            'rounded-full px-1.5 py-0.5 text-[9px] font-bold tracking-wide',
                            'bg-emerald-100 text-emerald-800' => $tab['chip'] === 'NEW',
                            'bg-slate-200 text-slate-600' => $tab['chip'] === 'BETA',
                        ])>{{ $tab['chip'] }}</span>
                    @endisset
                </button>
            @endforeach
        </div>

        {{-- ==================== Progress & S-Curve ==================== --}}
        <div data-demo-panel="progress" class="space-y-6">
            <section data-tour="progress-kpis" class="relative grid grid-cols-2 gap-3 lg:grid-cols-4 lg:gap-4">
                <div class="rounded-2xl border border-slate-200/60 bg-white p-4 shadow-sm sm:p-5">
                    <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase sm:text-[11px]">Planned target today</span>
                    <div class="mt-2 font-demo text-2xl font-extrabold text-demo-navy sm:text-3xl">40.4%</div>
                    <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-slate-100"><div class="h-full w-[40.4%] rounded-full bg-slate-400"></div></div>
                </div>
                <div class="rounded-2xl border border-slate-200/60 bg-white p-4 shadow-sm sm:p-5">
                    <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase sm:text-[11px]">Actual progress</span>
                    <div class="mt-2 font-demo text-2xl font-extrabold text-demo-green sm:text-3xl">42.0%</div>
                    <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-emerald-50"><div class="h-full w-[42%] rounded-full bg-demo-green"></div></div>
                </div>
                <div class="rounded-2xl border border-slate-200/60 bg-white p-4 shadow-sm sm:p-5">
                    <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase sm:text-[11px]">Variance</span>
                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        <span class="font-demo text-2xl font-extrabold text-demo-navy sm:text-3xl">+1.6%</span>
                        <span class="inline-flex items-center gap-0.5 rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-bold text-demo-green">
                            <span class="material-symbols-outlined text-[14px]">arrow_upward</span>
                            Ahead of plan
                        </span>
                    </div>
                </div>
                <div class="relative rounded-2xl border border-slate-200/60 bg-white p-4 shadow-sm sm:p-5">
                    <x-demo.info class="absolute top-3 right-3" />
                    <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase sm:text-[11px]">Days remaining</span>
                    <div class="mt-2 font-demo text-2xl font-extrabold text-demo-navy sm:text-3xl">151</div>
                    <p class="mt-2 text-xs text-slate-500">Turnover: <span class="font-semibold text-slate-700">Mar 31, 2027</span></p>
                </div>
            </section>

            <section data-tour="s-curve" class="relative rounded-2xl border border-slate-200/60 bg-white p-5 shadow-sm sm:p-6">
                <x-demo.info class="absolute top-5 right-5" />
                <div class="flex flex-wrap items-start justify-between gap-3 pr-8">
                    <div>
                        <h2 class="font-demo text-lg font-bold text-demo-navy">S-Curve — Cumulative Progress</h2>
                        <p class="text-xs text-slate-500">Planned target vs actual accomplishment, week by week</p>
                    </div>
                    <div class="flex items-center gap-4 rounded-full border border-slate-200/60 bg-slate-50 px-3.5 py-1.5 text-xs">
                        <span class="flex items-center gap-2 text-slate-600"><span class="w-4 border-t-2 border-dashed border-slate-400"></span>Planned</span>
                        <span class="flex items-center gap-2 font-semibold text-slate-800"><span class="h-1 w-4 rounded-full bg-demo-blue"></span>Actual</span>
                    </div>
                </div>

                <div class="-mx-2 mt-4 overflow-x-auto px-2">
                    <svg viewBox="0 0 1000 320" class="h-auto w-full min-w-[640px]" role="img" aria-label="S-Curve: planned 40.4% and actual 42.0% at week 18 of 40">
                        <defs>
                            <linearGradient id="actual-fill" x1="0" x2="0" y1="0" y2="1">
                                <stop offset="0%" stop-color="#3b5bdb" stop-opacity="0.25" />
                                <stop offset="100%" stop-color="#3b5bdb" stop-opacity="0" />
                            </linearGradient>
                        </defs>
                        @foreach ([0, 20, 40, 60, 80, 100] as $gridLine)
                            <line x1="60" x2="980" y1="{{ $chartY($gridLine) }}" y2="{{ $chartY($gridLine) }}" stroke="{{ $gridLine === 0 ? '#e2e8f0' : '#f1f5f9' }}" stroke-width="1" />
                            <text x="48" y="{{ $chartY($gridLine) + 4 }}" text-anchor="end" fill="#94a3b8" font-size="11">{{ $gridLine }}%</text>
                        @endforeach
                        @foreach ([0 => 'W1 · Jun 29', 8 => 'W8', 16 => 'W16', 24 => 'W24', 32 => 'W32', 40 => 'W40 · Mar 31'] as $week => $label)
                            <text x="{{ $chartX($week) }}" y="305" text-anchor="{{ $week === 0 ? 'start' : ($week === 40 ? 'end' : 'middle') }}" fill="#94a3b8" font-size="11">{{ $label }}</text>
                        @endforeach

                        <line x1="{{ $chartX(18) }}" x2="{{ $chartX(18) }}" y1="22" y2="280" stroke="#182338" stroke-opacity="0.45" stroke-width="1.5" stroke-dasharray="4 4" />
                        <rect x="{{ $chartX(18) - 42 }}" y="6" width="84" height="18" rx="9" fill="#182338" />
                        <text x="{{ $chartX(18) }}" y="19" text-anchor="middle" fill="#fff" font-size="10" font-weight="700">TODAY · W18</text>

                        <polyline points="{{ $plannedPoints }}" fill="none" stroke="#94a3b8" stroke-width="2" stroke-dasharray="6 5" />
                        <polygon points="{{ $chartX(0) }},280 {{ $actualPoints }} {{ $chartX(18) }},280" fill="url(#actual-fill)" />
                        <polyline points="{{ $actualPoints }}" fill="none" stroke="#3b5bdb" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="{{ $chartX(18) }}" cy="{{ $chartY(42) }}" r="9" fill="#3b5bdb" fill-opacity="0.2" />
                        <circle cx="{{ $chartX(18) }}" cy="{{ $chartY(42) }}" r="5" fill="#3b5bdb" stroke="#fff" stroke-width="2" />

                        <rect x="{{ $chartX(18) - 150 }}" y="{{ $chartY(42) - 44 }}" width="140" height="26" rx="8" fill="#182338" />
                        <text x="{{ $chartX(18) - 80 }}" y="{{ $chartY(42) - 27 }}" text-anchor="middle" fill="#fff" font-size="11" font-weight="700">42.0% actual <tspan fill="#6cf8bb">(+1.6%)</tspan></text>
                    </svg>
                </div>
            </section>

            <section data-tour="phase-breakdown" class="relative rounded-2xl border border-slate-200/60 bg-white p-5 shadow-sm sm:p-6">
                <x-demo.info class="absolute top-5 right-5" />
                <h2 class="pr-8 font-demo text-lg font-bold text-demo-navy">Phase Breakdown</h2>
                <p class="text-xs text-slate-500">Each phase's weight × its progress adds up to the overall percentage</p>

                <div class="-mx-2 mt-4 overflow-x-auto px-2">
                    <table class="w-full min-w-[680px] text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-semibold tracking-wider text-slate-400 uppercase">
                                <th class="px-3 py-2.5">Phase</th>
                                <th class="px-3 py-2.5 text-right">Weight</th>
                                <th class="px-3 py-2.5 text-right">Planned</th>
                                <th class="w-56 px-3 py-2.5">Actual</th>
                                <th class="px-3 py-2.5 text-right">Variance</th>
                                <th class="px-3 py-2.5 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($phases as $phase)
                                @php($status = $phase['actual'] >= 100 ? 'done' : ($phase['actual'] > 0 ? 'active' : 'idle'))
                                @php($variance = round($phase['actual'] - $phase['planned'], 1))
                                <tr @class(['bg-blue-50/40' => $status === 'active'])>
                                    <td @class(['px-3 py-3 font-semibold', 'text-slate-800' => $status !== 'idle', 'text-slate-500' => $status === 'idle'])>{{ $loop->iteration }}. {{ $phase['name'] }}</td>
                                    <td class="px-3 py-3 text-right text-slate-600">{{ $phase['weight'] }}%</td>
                                    <td class="px-3 py-3 text-right text-slate-600">{{ $phase['planned'] }}%</td>
                                    <td class="px-3 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="h-2 flex-1 overflow-hidden rounded-full bg-slate-100">
                                                <div @class(['h-full rounded-full', 'bg-demo-green' => $status === 'done', 'bg-demo-blue' => $status === 'active']) style="width: {{ $phase['actual'] }}%"></div>
                                            </div>
                                            <span @class(['w-12 text-right font-demo font-semibold', 'text-demo-blue' => $status === 'active', 'text-slate-700' => $status === 'done', 'text-slate-400' => $status === 'idle'])>{{ $phase['actual'] }}%</span>
                                        </div>
                                    </td>
                                    <td @class(['px-3 py-3 text-right', 'font-semibold text-demo-green' => $variance > 0, 'text-slate-400' => $variance <= 0])>{{ $variance > 0 ? '+'.$variance.'%' : '0' }}</td>
                                    <td class="px-3 py-3 text-center">
                                        @if ($status === 'done')
                                            <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-semibold text-demo-green">Done</span>
                                        @elseif ($status === 'active')
                                            <span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-[11px] font-semibold text-demo-blue">In progress</span>
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
                                <td class="px-3 py-3.5 text-right">100%</td>
                                <td class="px-3 py-3.5 text-right text-slate-600">40.4%</td>
                                <td class="px-3 py-3.5 text-sm text-demo-green">42.0%</td>
                                <td class="px-3 py-3.5 text-right text-demo-green">+1.6%</td>
                                <td class="px-3 py-3.5 text-center text-[11px] font-semibold text-slate-500">4 of 10 done</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>
        </div>

        {{-- ==================== Accomplishment Report ==================== --}}
        <div data-demo-panel="report" class="hidden">
            <section data-tour="accomplishment" class="relative rounded-2xl border border-slate-200/60 bg-white p-5 shadow-sm sm:p-6">
                <x-demo.info class="absolute top-5 right-5" />
                <h2 class="pr-8 font-demo text-lg font-bold text-demo-navy">Accomplishment Report</h2>
                <p class="text-xs text-slate-500">Each work item's progress, in pesos</p>

                <div class="mt-5 grid gap-3 sm:grid-cols-3 sm:gap-4">
                    <div class="rounded-xl border border-slate-200/50 bg-slate-50 p-4">
                        <span class="text-[11px] font-semibold tracking-wider text-slate-500 uppercase">Total contract amount</span>
                        <div class="mt-1 font-demo text-2xl font-extrabold text-demo-navy">₱10,200,000</div>
                    </div>
                    <div class="rounded-xl border border-emerald-100 bg-emerald-50/50 p-4">
                        <span class="text-[11px] font-semibold tracking-wider text-emerald-800 uppercase">Total accomplished</span>
                        <div class="mt-1 font-demo text-2xl font-extrabold text-demo-green">₱4,284,000</div>
                    </div>
                    <div class="rounded-xl border border-slate-200/50 bg-slate-50 p-4">
                        <span class="text-[11px] font-semibold tracking-wider text-slate-500 uppercase">Overall progress</span>
                        <div class="mt-1 font-demo text-2xl font-extrabold text-demo-navy">42.0%</div>
                    </div>
                </div>

                <div class="-mx-2 mt-5 overflow-x-auto px-2">
                    <table class="w-full min-w-[640px] text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] font-semibold tracking-wider text-slate-400 uppercase">
                                <th class="px-3 py-2.5">Item No.</th>
                                <th class="px-3 py-2.5">Particulars</th>
                                <th class="px-3 py-2.5 text-right">Weight %</th>
                                <th class="px-3 py-2.5 text-right">Amount (₱)</th>
                                <th class="px-3 py-2.5 text-right">% Accomp.</th>
                                <th class="px-3 py-2.5 text-right">Accomplished (₱)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($phases as $phase)
                                @php($amount = $contractPrice * $phase['weight'] / 100)
                                <tr @class(['bg-blue-50/30' => $phase['actual'] > 0 && $phase['actual'] < 100, 'text-slate-400' => $phase['actual'] == 0])>
                                    <td class="px-3 py-3 font-semibold text-slate-500">{{ $romanNumerals[$loop->index] }}</td>
                                    <td @class(['px-3 py-3 font-semibold', 'text-slate-800' => $phase['actual'] > 0])>{{ $phase['name'] }}</td>
                                    <td class="px-3 py-3 text-right">{{ $phase['weight'] }}%</td>
                                    <td class="px-3 py-3 text-right">{{ $peso($amount) }}</td>
                                    <td @class(['px-3 py-3 text-right font-semibold', 'text-demo-green' => $phase['actual'] >= 100, 'text-demo-blue' => $phase['actual'] > 0 && $phase['actual'] < 100])>{{ $phase['actual'] }}%</td>
                                    <td @class(['px-3 py-3 text-right font-semibold', 'text-slate-800' => $phase['actual'] > 0])>{{ $peso($phase['accomplished']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-slate-200 bg-slate-50/90 font-demo font-bold text-demo-navy">
                                <td class="px-3 py-3.5" colspan="2">Total</td>
                                <td class="px-3 py-3.5 text-right">100%</td>
                                <td class="px-3 py-3.5 text-right">₱10,200,000</td>
                                <td class="px-3 py-3.5 text-right">42.0%</td>
                                <td class="px-3 py-3.5 text-right text-demo-green">₱4,284,000</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>
        </div>

        {{-- ==================== AI Photo Analysis ==================== --}}
        <div data-demo-panel="photos" class="hidden">
            <div class="grid items-start gap-6 lg:grid-cols-12">
                <section data-tour="ai-queue" class="relative space-y-3 lg:col-span-4">
                    <div class="flex items-center justify-between px-1">
                        <h2 class="flex items-center gap-2 font-demo text-base font-bold text-demo-navy">
                            Analysis queue
                            <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[11px] font-bold text-demo-blue">4</span>
                        </h2>
                        <x-demo.info />
                    </div>
                    @foreach ($analysisQueue as $analysis)
                        <button type="button" @if (! $loop->first) data-demo-toast-message="{{ $analysis['status'] === 'accepted' ? 'Already reviewed: this analysis was accepted and its progress is official.' : 'AI is still reading these photos. The result appears here when it is ready.' }}" data-demo-toast-message-tl="{{ $analysis['status'] === 'accepted' ? 'Na-review na: tinanggap na ang analysis na ito at official na ang progress.' : 'Binabasa pa ng AI ang photos. Lalabas dito ang resulta kapag handa na.' }}" @endif
                            @class(['flex w-full gap-3 rounded-2xl bg-white p-3 text-left shadow-sm transition', 'border-2 border-demo-blue shadow-md' => $loop->first, 'border border-slate-200/80 hover:border-slate-300' => ! $loop->first])>
                            <img src="{{ asset('images/demo/'.$analysis['photo'].'.jpg') }}" alt="" class="size-16 shrink-0 rounded-xl border border-slate-200 object-cover sm:size-20">
                            <span class="flex flex-1 flex-col">
                                <span class="text-[13px] leading-snug font-semibold text-slate-900">{{ $analysis['note'] }}</span>
                                <span class="mt-0.5 text-[11px] text-slate-500">{{ $analysis['by'] }} · {{ $analysis['date'] }}</span>
                                <span class="mt-auto flex items-center justify-between gap-2 pt-1.5">
                                    <span class="inline-flex items-center gap-1 text-[11px] text-slate-500"><span class="material-symbols-outlined text-[14px]">photo_camera</span>{{ $analysis['photos'] }}</span>
                                    @if ($analysis['status'] === 'review')
                                        <span data-queue-status class="rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 text-[11px] font-semibold text-amber-700">Needs review</span>
                                    @elseif ($analysis['status'] === 'accepted')
                                        <span class="rounded-full border border-emerald-100 bg-emerald-50 px-2 py-0.5 text-[11px] font-semibold text-demo-green">Accepted</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full border border-indigo-100 bg-indigo-50 px-2 py-0.5 text-[11px] font-semibold text-indigo-700">
                                            <span class="size-2 animate-spin rounded-full border-2 border-indigo-600 border-t-transparent"></span>
                                            Analyzing…
                                        </span>
                                    @endif
                                </span>
                            </span>
                        </button>
                    @endforeach
                </section>

                <section class="rounded-2xl border border-slate-200/60 bg-white p-5 shadow-sm sm:p-6 lg:col-span-8">
                    <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 pb-4">
                        <div>
                            <h2 class="font-demo text-lg leading-snug font-bold text-demo-navy sm:text-xl">Second-floor slab rebar tying, about 60% done.</h2>
                            <p class="mt-1 text-xs text-slate-500"><span class="font-semibold text-slate-700">Ruben Santos</span> · Daily Work Log · Oct 31, 2026</p>
                        </div>
                        <span class="inline-flex items-center gap-1 rounded-full border border-indigo-200/60 bg-linear-to-r from-indigo-50 to-blue-50 px-3 py-1 text-xs font-bold text-indigo-700">
                            <span class="material-symbols-outlined text-[15px]">auto_awesome</span>
                            AI Photo Analysis
                        </span>
                    </div>

                    <div data-tour="ai-summary" class="relative">
                        <p class="mt-4 mb-2 text-[11px] font-bold tracking-wider text-slate-400 uppercase">Submitted photos (7)</p>
                        <div class="grid grid-cols-3 gap-2 sm:grid-cols-6">
                            @foreach (['fr-01', 'fr-10', 'site-01', 'site-02', 'site-03'] as $photo)
                                <img src="{{ asset('images/demo/'.$photo.'.jpg') }}" alt="" class="aspect-square w-full rounded-xl border border-slate-200 object-cover">
                            @endforeach
                            <span class="flex aspect-square items-center justify-center rounded-xl bg-demo-navy font-demo text-sm font-bold text-white">+2</span>
                        </div>

                        <div class="my-4 rounded-r-xl border-l-4 border-indigo-600 bg-indigo-50/50 p-4 text-sm leading-relaxed text-slate-700">
                            Photos show the second-floor slab's top and bottom rebar mats being tied across grids A–C, with spacers and chairs in place. Grids D–E are not visible in these photos.
                        </div>

                        <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                            <div class="rounded-xl border border-slate-200/50 bg-slate-50 p-3"><span class="text-[10px] font-semibold tracking-wider text-slate-500 uppercase">Construction stage</span><div class="mt-1 text-[13px] font-semibold text-slate-800">Structural · Slab reinforcement</div></div>
                            <div class="rounded-xl border border-slate-200/50 bg-slate-50 p-3"><span class="text-[10px] font-semibold tracking-wider text-slate-500 uppercase">Overall confidence</span><div class="mt-1 text-[13px] font-bold text-demo-green">High</div></div>
                            <div class="rounded-xl border border-slate-200/50 bg-slate-50 p-3"><span class="text-[10px] font-semibold tracking-wider text-slate-500 uppercase">Image quality</span><div class="mt-1 text-[13px] font-bold text-demo-blue">Good</div></div>
                            <div class="rounded-xl border border-slate-200/50 bg-slate-50 p-3"><span class="text-[10px] font-semibold tracking-wider text-slate-500 uppercase">Evidence coverage</span><div class="mt-1"><span class="rounded-full border border-amber-200/60 bg-amber-50 px-2 py-0.5 text-xs font-bold text-amber-700">Partial</span></div></div>
                        </div>

                        <div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 p-4 text-xs text-amber-900">
                            <p class="mb-1.5 flex items-center gap-1.5 font-bold"><span class="material-symbols-outlined text-[16px]">warning</span>Limitations &amp; Warnings</p>
                            <ul class="list-inside list-disc space-y-1">
                                <li>Bar sizes and spacing can't be verified from photos.</li>
                                <li>Grids D–E are not shown.</li>
                                <li>This is not an engineering inspection.</li>
                            </ul>
                        </div>
                    </div>

                    <div data-tour="ai-matches" class="relative mt-5">
                        <h3 class="font-demo text-base font-bold text-demo-navy">Milestone Assessment</h3>
                        <p class="text-xs text-slate-500">Milestone items these photos match, ranked by evidence</p>
                        <div class="-mx-2 mt-3 overflow-x-auto px-2">
                            <table class="w-full min-w-[640px] text-left text-xs">
                                <thead>
                                    <tr class="border-b border-slate-100 bg-slate-50/80 text-[10px] font-semibold tracking-wider text-slate-500 uppercase">
                                        <th class="px-3 py-2.5">Rank</th>
                                        <th class="px-3 py-2.5">Milestone item</th>
                                        <th class="px-3 py-2.5">Milestone</th>
                                        <th class="px-3 py-2.5 text-right">Current</th>
                                        <th class="px-3 py-2.5 text-center">Evidence</th>
                                        <th class="px-3 py-2.5 text-right">Suggested range</th>
                                        <th class="px-3 py-2.5 text-right">Recommended</th>
                                        <th class="px-3 py-2.5 text-right"><span class="sr-only">Action</span></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($milestoneMatches as $match)
                                        <tr data-match-row @class(['text-slate-400' => ! $match['recommended']])>
                                            <td class="px-3 py-3 font-semibold text-slate-400">#{{ $match['rank'] }}</td>
                                            <td @class(['px-3 py-3 text-[13px] font-semibold', 'text-slate-900' => $match['recommended']])>{{ $match['item'] }}</td>
                                            <td class="px-3 py-3">{{ $match['milestone'] }}</td>
                                            <td data-match-current class="px-3 py-3 text-right">{{ $match['current'] }}%</td>
                                            <td class="px-3 py-3 text-center">
                                                <span @class([
                                                    'rounded-full border px-2 py-0.5 text-[11px] font-semibold',
                                                    'border-emerald-100 bg-emerald-50 text-emerald-700' => $match['evidence'] === 'Strong',
                                                    'border-blue-100 bg-blue-50 text-blue-700' => $match['evidence'] === 'Supporting',
                                                    'border-slate-200 bg-slate-100 text-slate-600' => $match['evidence'] === 'Weak',
                                                ])>{{ $match['evidence'] }}</span>
                                            </td>
                                            <td class="px-3 py-3 text-right">{{ $match['range'] ?? 'Not assessed' }}</td>
                                            <td class="px-3 py-3 text-right font-bold">{{ $match['recommended'] ? $match['recommended'].'%' : '—' }}</td>
                                            <td class="px-3 py-3 text-right">
                                                @if ($match['recommended'])
                                                    <button type="button" data-accept-match data-recommended="{{ $match['recommended'] }}" data-item="{{ $match['item'] }}" @class([
                                                        'rounded-lg px-3 py-1.5 text-xs font-semibold whitespace-nowrap shadow-sm transition',
                                                        'bg-demo-blue text-white hover:bg-blue-700' => $match['rank'] === 1,
                                                        'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50' => $match['rank'] !== 1,
                                                    ])>Accept match</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p class="mt-4 flex items-center gap-2 border-t border-slate-100 pt-3 text-xs text-slate-500">
                            <span class="material-symbols-outlined text-[16px] text-slate-400">lock</span>
                            Nothing changes until you accept. AI never updates official progress on its own.
                        </p>
                    </div>
                </section>
            </div>
        </div>

        {{-- ==================== AI Insights (Beta) ==================== --}}
        {{-- A teaser only: beta features are not shown in detail on the public demo. --}}
        <div data-demo-panel="insights" class="hidden">
            <section data-tour="ai-insights" class="relative overflow-hidden rounded-2xl border border-indigo-100 bg-linear-to-br from-white to-indigo-50/60 p-6 shadow-sm sm:p-8">
                <span class="flex size-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600"><span class="material-symbols-outlined text-[26px]">auto_awesome</span></span>
                <h2 class="mt-4 flex items-center gap-2 font-demo text-xl font-extrabold text-demo-navy">
                    More AI is on the way
                    <span class="rounded bg-slate-200 px-1.5 py-0.5 text-[10px] font-semibold text-slate-600">BETA</span>
                </h2>
                <p class="mt-2 max-w-xl text-sm text-slate-600">We are building more AI that helps you spot what needs attention on site, early. As always, a person reviews everything before it counts.</p>
                <a href="{{ url('/') }}#demo" target="_top" data-demo-book class="mt-5 inline-flex items-center gap-1.5 rounded-full bg-demo-navy px-5 py-2.5 text-sm font-semibold text-white hover:bg-demo-navy/90">
                    Ask us about it in your demo
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </section>
        </div>
    </div>

    <x-slot:guide>
        @include('demo.partials.guide', ['steps' => $tourSteps])
    </x-slot:guide>
</x-demo.layout>
