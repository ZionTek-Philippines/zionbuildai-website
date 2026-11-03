@php
    // The first stop of "Try it!": how a day's attendance and site photos reach the contractor.
    // Ruben's Oct 31 time-out is the same work log shown in Field Reports and AI Project Monitoring.
    $siteSteps = [
        [
            'key' => 'time-in', 'number' => 1, 'photo' => 'hiw-01', 'device' => 'phone-in', 'ring' => 'left-[59%] top-[54%]',
            'label' => ['en' => 'Time in', 'tl' => 'Time in'],
            'en' => ['title' => 'Time in with a selfie', 'body' => 'When he arrives in the morning, Ruben, the foreman, picks his name on the phone and takes a selfie. That is his time-in.'],
            'tl' => ['title' => 'Time in gamit ang selfie', 'body' => 'Pagdating sa umaga, pipiliin ni Ruben, ang foreman, ang pangalan niya sa phone at magse-selfie. Yan na ang time-in niya.'],
        ],
        [
            'key' => 'time-out', 'number' => 2, 'photo' => 'hiw-02', 'device' => 'phone-out', 'ring' => 'left-[52%] top-[38%]',
            'label' => ['en' => 'Time out & site photos', 'tl' => 'Time out at site photos'],
            'en' => ['title' => 'Photos of the work before going home', 'body' => 'Before leaving, he times out, snaps photos of the work and answers one question: "Ano ginawa mo today?"'],
            'tl' => ['title' => 'Photos ng trabaho bago umuwi', 'body' => 'Bago umuwi, magta-time out siya, kukuha ng photos ng trabaho, at sasagutin ang isang tanong: "Ano ginawa mo today?"'],
        ],
        [
            'key' => 'contractor', 'number' => 3, 'photo' => 'hiw-03', 'device' => 'laptop', 'ring' => 'left-[30%] top-[58%]',
            'label' => ['en' => 'You see it', 'tl' => 'Kita mo agad'],
            'en' => ['title' => 'You see it on your laptop', 'body' => 'The photos and the work log land in Field Reports, with who did the work and when. No more digging through group chats.'],
            'tl' => ['title' => 'Kita mo sa laptop mo', 'body' => 'Pumapasok sa Field Reports ang photos at work log, kasama kung sino ang gumawa at kailan. Hindi mo na kailangang maghalungkat sa GC.'],
        ],
    ];

    $laptopReports = [
        ['note' => 'Second-floor slab rebar tying, about 60% done.', 'by' => 'Ruben Santos', 'photo' => 'fr-01', 'photos' => 7, 'isNew' => true],
        ['note' => 'Nag-tali ng rebar sa grid B–C, 2nd floor slab.', 'by' => 'Danilo Lim', 'photo' => 'fr-02', 'photos' => 3, 'isNew' => false],
        ['note' => 'Installed electrical conduits on the 2nd floor slab.', 'by' => 'Jessie Tan', 'photo' => 'fr-03', 'photos' => 2, 'isNew' => false],
    ];

    $tourSteps = [
        [
            'target' => null,
            'en' => ['title' => 'How ZionBuild works on site', 'body' => 'Before the dashboards, see where the data comes from: a phone on site and your laptop in the office. Three steps.'],
            'tl' => ['title' => 'Paano gumagana ang ZionBuild sa site', 'body' => 'Bago ang dashboards, tingnan muna kung saan galing ang data: isang phone sa site at ang laptop mo sa office. Tatlong steps lang.'],
        ],
        [
            'target' => 'step-time-in',
            'en' => ['title' => 'Step 1: Time in', 'body' => 'Pick your name, take a selfie, tap Time-in. That is the whole morning routine.', 'example' => 'Ruben times in at 7:02 AM at the Ong Residential gate.'],
            'tl' => ['title' => 'Step 1: Time in', 'body' => 'Piliin ang pangalan, mag-selfie, i-tap ang Time-in. Yan na ang buong routine sa umaga.', 'example' => 'Nag-time in si Ruben ng 7:02 AM sa gate ng Ong Residential.'],
        ],
        [
            'target' => 'step-time-out',
            'en' => ['title' => 'Step 2: Time out with photos', 'body' => 'At the end of the day, the time-out comes with photos of the work and one line about what got done.', 'example' => '7 photos and "Second-floor slab rebar tying, about 60% done."'],
            'tl' => ['title' => 'Step 2: Time out na may photos', 'body' => 'Sa dulo ng araw, kasama ng time-out ang photos ng trabaho at isang linya tungkol sa natapos.', 'example' => '7 photos at "Second-floor slab rebar tying, about 60% done."'],
        ],
        [
            'target' => 'step-contractor',
            'en' => ['title' => 'Step 3: You see it', 'body' => 'Everything lands in Field Reports. From there it feeds your Command Center, progress and payroll.', 'example' => 'You open your laptop and Ruben\'s 7 photos are at the top of Field Reports.'],
            'tl' => ['title' => 'Step 3: Kita mo agad', 'body' => 'Lahat napupunta sa Field Reports. Mula doon, napupunta ito sa Command Center, progress at payroll mo.', 'example' => 'Pagbukas mo ng laptop, nasa itaas ng Field Reports ang 7 photos ni Ruben.'],
        ],
        [
            'target' => null, 'final' => true,
            'next' => ['label' => ['en' => 'Command Center', 'tl' => 'Command Center'], 'url' => route('demo.command-center')],
            'en' => ['title' => 'Now see what the contractor sees', 'body' => 'Next: the Command Center, your whole project on one screen. Or book a demo to see it with your own projects.'],
            'tl' => ['title' => 'Ngayon, tingnan ang nakikita ng contractor', 'body' => 'Susunod: ang Command Center, ang buong project mo sa isang screen. O mag-book ng demo para makita ito gamit ang sarili mong projects.'],
        ],
    ];
@endphp

<x-demo.layout title="How It Works" active="how-it-works" active-icon="menu_book">
    <div class="space-y-10">
        <section class="mx-auto max-w-3xl pt-2 text-center">
            <span class="inline-flex items-center gap-1.5 rounded-full border border-violet-200 bg-violet-100 px-3.5 py-1 text-xs font-semibold tracking-wider text-demo-violet uppercase">
                <span class="material-symbols-outlined text-[16px]">smartphone</span>
                <x-demo.t en="How it works on site" tl="Paano ito gumagana sa site" />
            </span>
            <h1 class="mt-4 font-demo text-3xl font-extrabold tracking-tight text-demo-navy sm:text-4xl">
                <x-demo.t en="From the site to your screen in 3 steps" tl="Mula site hanggang screen mo, sa 3 steps" />
            </h1>
            <p class="mt-3 text-base text-slate-600 sm:text-lg">
                <x-demo.t
                    en="Your foreman records attendance and site photos on a phone. You see them on your laptop, wherever you are."
                    tl="Sa phone nire-record ng foreman mo ang attendance at site photos. Makikita mo ito sa laptop mo, nasaan ka man." />
            </p>
        </section>

        @foreach ($siteSteps as $step)
            <section data-tour="step-{{ $step['key'] }}" class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-900/5 sm:p-8">
                <div class="flex flex-wrap items-center gap-3 border-b border-slate-100 pb-4">
                    <span class="rounded-xl bg-demo-navy px-3 py-1.5 font-demo text-xs font-extrabold tracking-wider text-white uppercase">
                        Step {{ $step['number'] }} · <x-demo.t :en="$step['label']['en']" :tl="$step['label']['tl']" />
                    </span>
                    <h2 class="font-demo text-lg font-bold text-demo-navy sm:text-xl"><x-demo.t :en="$step['en']['title']" :tl="$step['tl']['title']" /></h2>
                </div>

                <div class="mt-6 grid items-center gap-6 xl:grid-cols-[minmax(0,1fr)_64px_auto]">
                    <div class="space-y-4">
                        <div class="relative overflow-hidden rounded-2xl shadow-md ring-1 ring-slate-200">
                            <img src="{{ asset('images/demo/'.$step['photo'].'.jpg') }}" alt="" class="aspect-75/56 w-full object-cover">
                            {{-- Marks the device in the photo; the zoomed screen is on the right --}}
                            <span class="absolute {{ $step['ring'] }} size-16 -translate-x-1/2 -translate-y-1/2 rounded-full border-[3px] border-dashed border-white shadow-[0_0_0_9999px_rgb(15_23_42/0.18)]" aria-hidden="true"></span>
                        </div>
                        <p class="rounded-xl bg-slate-50 px-4 py-3 text-sm leading-relaxed text-slate-700 ring-1 ring-slate-200">
                            <x-demo.t :en="$step['en']['body']" :tl="$step['tl']['body']" />
                        </p>
                    </div>

                    <div class="flex items-center justify-center text-demo-violet xl:flex-col" aria-hidden="true">
                        <span class="h-10 border-l-2 border-dashed border-demo-violet/50 xl:h-auto xl:w-full xl:border-t-2 xl:border-l-0"></span>
                        <span class="hidden xl:mt-1 xl:flex"><span class="material-symbols-outlined text-[22px]">arrow_forward</span></span>
                        <span class="flex xl:hidden"><span class="material-symbols-outlined text-[22px]">arrow_downward</span></span>
                    </div>

                    <div class="flex justify-center">
                        @switch($step['device'])
                            @case('phone-in')
                                <x-demo.phone time="7:02">
                                    <div class="flex items-center justify-between border-b border-slate-100 px-4 py-2">
                                        <span class="font-demo text-[13px] font-extrabold text-demo-navy">zionbuild</span>
                                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">Ong Residential</span>
                                    </div>
                                    <div class="flex flex-1 flex-col px-4 pt-3 pb-1">
                                        <h3 class="font-demo text-lg font-extrabold text-demo-navy">Time-in</h3>

                                        <span class="mt-3 text-[10px] font-semibold text-slate-500 uppercase">Employee</span>
                                        <span class="mt-1 flex items-center justify-between rounded-xl border border-slate-300 px-3 py-2 text-[13px] font-semibold text-slate-800">
                                            Ruben Santos
                                            <span class="material-symbols-outlined text-[18px] text-slate-400">expand_more</span>
                                        </span>

                                        <span class="mt-4 text-[10px] font-semibold text-slate-500 uppercase">Selfie</span>
                                        <div class="mt-1 flex flex-col items-center rounded-2xl bg-slate-50 py-4 ring-1 ring-slate-200">
                                            <span class="size-28 rounded-full bg-size-[450%] bg-position-[69%_15%] ring-4 ring-white shadow-md" style="background-image: url('{{ asset('images/demo/hiw-01.jpg') }}')" role="img" aria-label="Selfie"></span>
                                            <span class="mt-2.5 inline-flex items-center gap-1 text-[11px] font-semibold text-demo-blue">
                                                <span class="material-symbols-outlined text-[15px]">photo_camera</span>
                                                Retake
                                            </span>
                                        </div>

                                        <span class="mt-auto flex items-center justify-center gap-1.5 rounded-xl bg-demo-blue py-3 font-demo text-sm font-bold text-white shadow-md">
                                            <span class="material-symbols-outlined text-[18px]">login</span>
                                            Time-in
                                        </span>
                                    </div>
                                </x-demo.phone>
                                @break

                            @case('phone-out')
                                <x-demo.phone time="5:04">
                                    <div class="flex items-center justify-between border-b border-slate-100 px-4 py-2">
                                        <span class="font-demo text-[13px] font-extrabold text-demo-navy">zionbuild</span>
                                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">Ong Residential</span>
                                    </div>
                                    <div class="flex flex-1 flex-col px-4 pt-3 pb-1">
                                        <h3 class="font-demo text-lg font-extrabold text-demo-navy">Time-out</h3>

                                        <span class="mt-2 text-[10px] font-semibold text-slate-500 uppercase">Employee</span>
                                        <span class="mt-1 flex items-center justify-between rounded-xl border border-slate-300 px-3 py-1.5 text-[12px] font-semibold text-slate-800">
                                            Ruben Santos
                                            <span class="material-symbols-outlined text-[18px] text-slate-400">expand_more</span>
                                        </span>

                                        <span class="mt-2.5 text-[10px] font-semibold text-slate-500 uppercase">Photos / video</span>
                                        <div class="mt-1 grid grid-cols-4 gap-1.5">
                                            @foreach (['fr-01', 'fr-04', 'fr-06'] as $thumb)
                                                <img src="{{ asset('images/demo/'.$thumb.'.jpg') }}" alt="" class="aspect-square w-full rounded-lg object-cover">
                                            @endforeach
                                            <span class="flex aspect-square items-center justify-center rounded-lg bg-slate-100 text-[11px] font-bold text-slate-600">+4</span>
                                        </div>

                                        <span class="mt-2.5 text-[10px] font-semibold text-slate-500 uppercase">Ano ginawa mo today?</span>
                                        <span class="mt-1 rounded-xl border border-slate-300 px-3 py-2 text-[12px] leading-snug text-slate-800">Second-floor slab rebar tying, about 60% done.</span>

                                        <span class="mt-2.5 text-[10px] font-semibold text-slate-500 uppercase">Task <span class="normal-case">(optional)</span></span>
                                        <span class="mt-1 flex items-center justify-between rounded-xl border border-slate-200 px-3 py-1.5 text-[12px] text-slate-400">
                                            Select a task
                                            <span class="material-symbols-outlined text-[18px]">expand_more</span>
                                        </span>

                                        <span class="mt-2.5 text-[10px] font-semibold text-slate-500 uppercase">Work date</span>
                                        <span class="mt-1 rounded-xl border border-slate-300 px-3 py-1.5 text-[12px] font-semibold text-slate-800">Oct 31, 2026</span>

                                        <span class="mt-auto flex items-center justify-center gap-1.5 rounded-xl bg-demo-blue py-3 font-demo text-sm font-bold text-white shadow-md">
                                            <span class="material-symbols-outlined text-[18px]">send</span>
                                            Submit
                                        </span>
                                    </div>
                                </x-demo.phone>
                                @break

                            @case('laptop')
                                {{-- A laptop at real proportions: 16:10 screen in a thin bezel, hinge, then the wider base with its thumb notch --}}
                                <div class="w-full max-w-135 px-[4%] xl:w-135">
                                    <div class="rounded-t-2xl bg-slate-950 p-2.5 pb-3 shadow-2xl ring-1 ring-slate-700">
                                        <span class="mx-auto mb-1.5 block size-1.5 rounded-full bg-slate-700" aria-hidden="true"></span>
                                        <div class="flex aspect-16/10 flex-col overflow-hidden rounded-sm bg-[#f6fafe]">
                                            <div class="flex h-7 shrink-0 items-center gap-2 bg-linear-to-r from-demo-indigo to-demo-violet px-3 text-white">
                                                <span class="font-demo text-[11px] font-extrabold">zionbuild</span>
                                                <span class="rounded-full bg-white/15 px-2 py-0.5 text-[8px] font-semibold">Ong Residential</span>
                                            </div>
                                            <div class="flex min-h-0 flex-1 flex-col p-2.5 sm:p-3">
                                                <div class="flex items-center justify-between gap-2">
                                                    <span class="font-demo text-[13px] font-extrabold text-demo-navy">Field Reports</span>
                                                    <span class="text-[8px] text-slate-500">Sat, Oct 31, 2026</span>
                                                </div>
                                                <div class="mt-1.5 flex flex-wrap gap-1 text-[8px] font-semibold">
                                                    <span class="rounded-full bg-blue-50 px-2 py-0.5 text-demo-blue">14 workers timed in</span>
                                                    <span class="rounded-full bg-amber-50 px-2 py-0.5 text-amber-700">3 work logs</span>
                                                    <span class="rounded-full bg-purple-50 px-2 py-0.5 text-purple-700">12 photos</span>
                                                </div>
                                                <div class="mt-2 grid min-h-0 flex-1 grid-cols-3 gap-1.5 sm:gap-2">
                                                    @foreach ($laptopReports as $report)
                                                        <div @class([
                                                            'flex min-h-0 flex-col overflow-hidden rounded-lg bg-white shadow-sm',
                                                            'ring-2 ring-demo-violet' => $report['isNew'],
                                                            'ring-1 ring-slate-200' => ! $report['isNew'],
                                                        ])>
                                                            <div class="relative min-h-0 flex-1">
                                                                <img src="{{ asset('images/demo/'.$report['photo'].'.jpg') }}" alt="" class="absolute inset-0 size-full object-cover">
                                                                <span class="absolute right-1 bottom-1 rounded bg-black/60 px-1 text-[7px] font-semibold text-white">{{ $report['photos'] }} photos</span>
                                                                @if ($report['isNew'])
                                                                    <span class="absolute top-1 left-1 rounded bg-demo-violet px-1 text-[7px] font-bold text-white">NEW</span>
                                                                @endif
                                                            </div>
                                                            <div class="p-1.5">
                                                                <p class="line-clamp-2 text-[8px] leading-tight font-semibold text-slate-800">{{ $report['note'] }}</p>
                                                                <p class="mt-0.5 truncate text-[7px] text-slate-500">{{ $report['by'] }} · Oct 31</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="h-1.5 bg-linear-to-b from-slate-500 to-slate-400" aria-hidden="true"></div>
                                    <div class="relative mx-[-4.5%] h-3.5 rounded-b-2xl bg-linear-to-b from-slate-300 to-slate-400 shadow-[0_14px_24px_-10px_rgb(15_23_42/0.45)]" aria-hidden="true">
                                        <span class="absolute top-0 left-1/2 h-1.5 w-20 -translate-x-1/2 rounded-b-md bg-slate-400/80"></span>
                                    </div>
                                </div>
                                @break
                        @endswitch
                    </div>
                </div>
            </section>
        @endforeach

        <section class="mx-auto max-w-xl pb-2 text-center">
            <h2 class="font-demo text-2xl font-extrabold text-demo-navy sm:text-3xl">
                <x-demo.t en="Now see what the contractor sees." tl="Ngayon, tingnan ang nakikita ng contractor." />
            </h2>
            <p class="mt-2 text-sm text-slate-600">
                <x-demo.t en="The Command Center puts the whole project on one screen." tl="Nasa isang screen ang buong project sa Command Center." />
            </p>
            <a href="{{ route('demo.command-center') }}" class="mt-6 inline-flex items-center gap-2 rounded-full bg-demo-navy px-7 py-3.5 font-demo text-base font-bold text-white shadow-lg transition hover:bg-demo-navy/90">
                <x-demo.t en="Next: Command Center" tl="Susunod: Command Center" />
                <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
            </a>
        </section>
    </div>

    <x-slot:guide>
        @include('demo.partials.guide', ['steps' => $tourSteps])
    </x-slot:guide>
</x-demo.layout>
