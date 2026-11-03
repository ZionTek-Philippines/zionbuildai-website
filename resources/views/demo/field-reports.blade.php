@php
    $workLogs = [
        ['note' => 'Second-floor slab rebar tying, about 60% done.', 'by' => 'Ruben Santos', 'type' => 'Daily Work Log', 'date' => 'Oct 31, 2026', 'photo' => 'fr-01', 'photos' => 7, 'visible' => true, 'thisWeek' => true],
        ['note' => 'Nag-tali ng rebar sa grid B–C, 2nd floor slab.', 'by' => 'Danilo Lim', 'type' => 'Daily Work Log', 'date' => 'Oct 31, 2026', 'photo' => 'fr-02', 'photos' => 3, 'visible' => false, 'thisWeek' => true],
        ['note' => 'Installed electrical conduits on the 2nd floor slab.', 'by' => 'Jessie Tan', 'type' => 'Daily Work Log', 'date' => 'Oct 31, 2026', 'photo' => 'fr-03', 'photos' => 2, 'visible' => false, 'thisWeek' => true],
        ['note' => 'Slab formworks installed on grids A–C.', 'by' => 'Ruben Santos', 'type' => 'Daily Work Log', 'date' => 'Oct 30, 2026', 'photo' => 'fr-04', 'photos' => 5, 'visible' => true, 'thisWeek' => true],
        ['note' => 'Nag-cut at install ng marine plywood sa formworks.', 'by' => 'Arnel Ocampo', 'type' => 'Daily Work Log', 'date' => 'Oct 30, 2026', 'photo' => 'fr-05', 'photos' => 3, 'visible' => false, 'thisWeek' => true],
        ['note' => 'Deformed bars 12mm delivered and checked.', 'by' => 'Ruben Santos', 'type' => 'Daily Work Log', 'date' => 'Oct 29, 2026', 'photo' => 'fr-06', 'photos' => 2, 'visible' => false, 'thisWeek' => true],
        ['note' => 'Plastering of ground-floor CHB walls, east side.', 'by' => 'Mateo Cruz', 'type' => 'Daily Work Log', 'date' => 'Oct 27, 2026', 'photo' => 'fr-08', 'photos' => 4, 'visible' => false, 'thisWeek' => true],
        ['note' => 'Scaffolding and shoring posts installed.', 'by' => 'Ruben Santos', 'type' => 'Daily Work Log', 'date' => 'Oct 26, 2026', 'photo' => 'fr-09', 'photos' => 3, 'visible' => true, 'thisWeek' => true],
    ];

    $progressUpdates = [
        ['note' => 'Ground-floor CHB walls completed. Second-floor slab preparation started.', 'by' => 'Engr. Paolo Reyes', 'type' => 'Progress Update', 'date' => 'Oct 28, 2026', 'photo' => 'fr-07', 'photos' => 8, 'visible' => true, 'thisWeek' => true],
        ['note' => 'Second-floor beams poured and cured.', 'by' => 'Engr. Paolo Reyes', 'type' => 'Progress Update', 'date' => 'Oct 13, 2026', 'photo' => 'site-04', 'photos' => 6, 'visible' => true, 'thisWeek' => false],
        ['note' => 'Ground-floor columns completed.', 'by' => 'Engr. Paolo Reyes', 'type' => 'Progress Update', 'date' => 'Sep 08, 2026', 'photo' => 'site-10', 'photos' => 5, 'visible' => true, 'thisWeek' => false],
        ['note' => 'Raised ground-floor fill compacted and leveled.', 'by' => 'Engr. Paolo Reyes', 'type' => 'Progress Update', 'date' => 'Aug 18, 2026', 'photo' => 'site-13', 'photos' => 4, 'visible' => false, 'thisWeek' => false],
    ];

    $todayStats = [
        ['label' => 'Timed in today', 'value' => '14 workers', 'icon' => 'groups', 'tint' => 'bg-blue-50 text-demo-blue'],
        ['label' => 'Work logs today', 'value' => '3', 'icon' => 'receipt_long', 'tint' => 'bg-amber-50 text-amber-700'],
        ['label' => 'Photos today', 'value' => '12', 'icon' => 'photo_camera', 'tint' => 'bg-purple-50 text-purple-700'],
    ];

    $sharedThisWeek = collect([...$workLogs, ...$progressUpdates])->where('thisWeek', true)->where('visible', true)->count();

    // Each step: English + Taglish copy, with an optional example drawn from the sample data above.
    $tourSteps = [
        [
            'target' => null,
            'en' => ['title' => 'Field Reports: the site, on your phone', 'body' => 'Every photo and work log your team sends from the site lands here. See what got done today, even from the office.'],
            'tl' => ['title' => 'Field Reports: ang site, nasa phone mo', 'body' => 'Lahat ng photos at work logs na pinapadala ng team mo mula sa site, dito napupunta. Kita mo ang ginawa today, kahit nasa office ka.'],
        ],
        [
            'target' => 'today-strip',
            'en' => ['title' => 'Today at a glance', 'body' => 'Who timed in, how many work logs and photos came in today, and how many updates you shared with the client this week.', 'example' => 'By 5 PM: 14 workers timed in, 3 work logs and 12 photos from the site.'],
            'tl' => ['title' => 'Ngayong araw, isang tingin', 'body' => 'Sino ang nag-time in, ilang work logs at photos ang pumasok today, at ilang updates ang na-share mo sa client this week.', 'example' => 'Pagdating ng 5 PM: 14 ang nag-time in, 3 work logs at 12 photos galing sa site.'],
        ],
        [
            'target' => 'new-report',
            'en' => ['title' => 'How workers report', 'body' => 'Before going home, a worker snaps photos and answers one question: "What did you do today?" Click this button to see the form.', 'example' => 'Danilo Lim, steelman, sends 3 photos and one line about the rebar he tied on the 2nd floor slab.'],
            'tl' => ['title' => 'Paano mag-report ang workers', 'body' => 'Bago umuwi, kukuha ng photos ang worker at sasagutin ang isang tanong: "Ano ginawa mo today?" I-click ang button na ito para makita ang form.', 'example' => 'Nagpadala si Danilo Lim, steelman, ng 3 photos: "Nag-tali ng rebar sa grid B–C, 2nd floor slab."'],
        ],
        [
            'target' => 'report-tabs',
            'en' => ['title' => 'Work logs and progress updates', 'body' => 'Daily Work Logs come from your workers. Progress Updates are written by your engineer or project manager.', 'example' => 'Workers log what they did each day; Engr. Paolo Reyes writes the update the homeowner reads.'],
            'tl' => ['title' => 'Work logs at progress updates', 'body' => 'Galing sa workers ang Daily Work Logs. Ang Progress Updates naman ay sinusulat ng engineer o project manager mo.', 'example' => 'Ang workers ang nagla-log ng ginawa nila araw-araw; si Engr. Paolo Reyes ang sumusulat ng update na binabasa ng homeowner.'],
        ],
        [
            'target' => 'report-card',
            'en' => ['title' => 'Every log comes with proof', 'body' => 'Photos, who did the work, the date, and whether the client can see it. No more digging through group chats.', 'example' => 'Need proof the formworks on grids A–C were done on Oct 30? It\'s here with 5 photos.'],
            'tl' => ['title' => 'Bawat log, may patunay', 'body' => 'Photos, sino ang gumawa, kailan, at kung kita ba ng client. Hindi mo na kailangang maghalungkat sa GC.', 'example' => 'Kailangan ng patunay na tapos ang formworks sa grids A–C noong Oct 30? Nandito, may 5 photos.'],
        ],
        [
            'target' => 'client-toggle',
            'en' => ['title' => 'You decide what the client sees', 'body' => 'Only the updates you share appear in the homeowner\'s Client Portal. Try it: click "Hide from client".', 'example' => 'Share the photos of the finished slab; keep the mid-pour shots for your team.'],
            'tl' => ['title' => 'Ikaw ang bahala kung ano ang makikita ng client', 'body' => 'Ang mga updates lang na shine-share mo ang lalabas sa Client Portal ng homeowner. Subukan: i-click ang "Hide from client".', 'example' => 'I-share ang photos ng tapos na slab; sa team mo lang muna ang photos habang nagbubuhos.'],
        ],
        [
            'target' => 'ai-analyze',
            'en' => ['title' => 'AI photo analysis', 'body' => 'One click and AI reviews the photos, then suggests which task or milestone they match and how far along it looks. You confirm it; AI never changes progress on its own.', 'example' => 'Today\'s rebar photos are matched to "Second-floor slab: slab reinforcement", suggested at 55–65% done, waiting for your OK.'],
            'tl' => ['title' => 'AI photo analysis', 'body' => 'Isang click lang, rerebyuhin ng AI ang photos at sasabihin kung anong task o milestone ang tugma at gaano na kalayo. Ikaw ang magko-confirm; hindi kailanman babaguhin ng AI ang progress nang mag-isa.', 'example' => 'Ang rebar photos today, na-match sa "Second-floor slab: slab reinforcement", mga 55–65% na, hinihintay ang OK mo.'],
        ],
        [
            'target' => null, 'final' => true,
            'next' => ['label' => ['en' => 'AI Project Monitoring', 'tl' => 'AI Project Monitoring'], 'url' => route('demo.ai-project-monitoring')],
            'en' => ['title' => "That's Field Reports", 'body' => 'Photos and work logs from the site, organized and ready to share. Want to set this up for your own projects?'],
            'tl' => ['title' => 'Yan ang Field Reports', 'body' => 'Photos at work logs mula sa site, organisado at handang i-share. Gusto mo bang i-set up ito para sa sarili mong projects?'],
        ],
    ];
@endphp

<x-demo.layout title="Field Reports" active="field-reports" active-icon="photo_camera">
    <div class="space-y-6">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="font-demo text-[28px] font-extrabold tracking-tight text-demo-navy">Field Reports</h1>
                <p class="text-sm text-slate-500">Photos and work logs sent from the site.</p>
            </div>
            <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-600">
                <span class="material-symbols-outlined text-[15px]">calendar_today</span>
                Today: Sat, Oct 31, 2026
            </span>
        </div>

        <section data-tour="today-strip" class="relative grid grid-cols-2 gap-3 lg:grid-cols-4 lg:gap-4">
            @foreach ($todayStats as $stat)
                <div class="flex items-center gap-3 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-xl {{ $stat['tint'] }}">
                        <span class="material-symbols-outlined text-[22px]">{{ $stat['icon'] }}</span>
                    </span>
                    <div>
                        <span class="block text-[10px] font-bold tracking-wider text-slate-400 uppercase">{{ $stat['label'] }}</span>
                        <span class="font-demo text-base font-extrabold whitespace-nowrap text-demo-navy sm:text-xl">{{ $stat['value'] }}</span>
                    </div>
                </div>
            @endforeach
            <div class="relative flex items-center gap-3 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-demo-green">
                    <span class="material-symbols-outlined text-[22px]">share</span>
                </span>
                <div class="flex-1">
                    <span class="block text-[10px] font-bold tracking-wider text-slate-400 uppercase">Shared with client</span>
                    <span class="font-demo text-base font-extrabold whitespace-nowrap text-demo-green sm:text-xl"><span data-shared-count>{{ $sharedThisWeek }}</span> this week</span>
                </div>
                <x-demo.info class="absolute top-2 right-2" />
            </div>
        </section>

        <div class="flex flex-col gap-3 rounded-2xl border border-slate-200/80 bg-white p-3 shadow-sm lg:flex-row lg:items-center lg:justify-between">
            <div data-tour="report-tabs" role="tablist" class="flex gap-1 self-start rounded-xl bg-slate-100 p-1">
                <button type="button" role="tab" aria-selected="true" data-report-tab="logs" class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-xs font-medium text-slate-600 transition hover:bg-slate-200/60 aria-selected:bg-demo-navy aria-selected:font-semibold aria-selected:text-white">
                    Daily Work Logs
                    <span class="rounded-full bg-black/10 px-1.5 text-[10px]">{{ count($workLogs) }}</span>
                </button>
                <button type="button" role="tab" aria-selected="false" data-report-tab="updates" class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-xs font-medium text-slate-600 transition hover:bg-slate-200/60 aria-selected:bg-demo-navy aria-selected:font-semibold aria-selected:text-white">
                    Progress Updates
                    <span class="rounded-full bg-black/10 px-1.5 text-[10px]">{{ count($progressUpdates) }}</span>
                </button>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <span class="hidden items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50/60 px-3 py-1.5 text-xs text-slate-400 md:inline-flex">
                    <span class="material-symbols-outlined text-[16px]">search</span>
                    Search logs…
                </span>
                <span class="inline-flex items-center gap-1 rounded-xl border border-slate-200 px-3 py-1.5 text-xs text-slate-700">
                    <span class="text-[11px] text-slate-400">Performed By:</span>
                    <span class="font-semibold">All</span>
                    <span class="material-symbols-outlined text-[16px] text-slate-400">expand_more</span>
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 px-3 py-1.5 text-xs text-slate-700">
                    <span class="material-symbols-outlined text-[15px] text-slate-400">calendar_month</span>
                    Oct 24 – Oct 31
                </span>
                <button type="button" data-tour="new-report" data-report-open class="inline-flex items-center gap-1.5 rounded-xl bg-red-600 px-3.5 py-1.5 text-xs font-bold text-white shadow-sm transition hover:bg-red-700 active:scale-95">
                    <span class="material-symbols-outlined text-[16px]">add_a_photo</span>
                    <span data-report-panel-label="logs">New Photo of Accomplishment</span>
                    <span data-report-panel-label="updates" class="hidden">New Progress Update</span>
                </button>
            </div>
        </div>

        <div data-report-panel="logs" role="tabpanel">
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($workLogs as $report)
                    @include('demo.partials.report-card', ['report' => $report, 'selectable' => false, 'isTourCard' => $loop->first])
                @endforeach
            </div>
        </div>

        <div data-report-panel="updates" role="tabpanel" class="hidden">
            <p class="mb-4 flex items-center gap-2 text-xs text-slate-500">
                <span class="material-symbols-outlined text-[16px] text-demo-blue">call_merge</span>
                <x-demo.t en="Tick two or more updates to merge them into one report for the client." tl="Pumili ng dalawa o higit pang updates para pagsamahin sa isang report para sa client." />
            </p>
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($progressUpdates as $report)
                    @include('demo.partials.report-card', ['report' => $report, 'selectable' => true, 'isTourCard' => false])
                @endforeach
            </div>
        </div>
    </div>

    {{-- Merge bar for selected progress updates --}}
    <div data-merge-bar class="fixed bottom-20 left-1/2 z-40 hidden -translate-x-1/2 items-center gap-4 rounded-full border border-slate-700/60 bg-demo-navy px-5 py-3 text-white shadow-2xl sm:bottom-6">
        <span class="flex items-center gap-2 font-demo text-sm font-bold">
            <span data-merge-count class="flex size-5 items-center justify-center rounded-full bg-demo-blue text-[11px]">0</span>
            Selected
        </span>
        <span class="h-4 w-px bg-slate-600"></span>
        <button type="button" data-merge-action class="inline-flex items-center gap-1.5 text-xs font-semibold hover:text-blue-300">
            <span class="material-symbols-outlined text-[16px]">call_merge</span>
            Merge Updates
        </button>
        <span class="h-4 w-px bg-slate-600"></span>
        <button type="button" data-merge-clear class="text-xs text-slate-400 hover:text-white">Clear</button>
    </div>

    @include('demo.partials.report-form')

    <x-slot:guide>
        @include('demo.partials.guide', ['steps' => $tourSteps])
    </x-slot:guide>
</x-demo.layout>
