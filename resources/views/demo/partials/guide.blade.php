{{--
    Demo walkthrough (pattern from ramcarspendflow's x-user-guide): Guide spotlights the page's [data-tour]
    sections one step at a time; Explore puts a glowing dot on each. Steps carry English and Taglish copy
    plus an optional example. Behaviour lives in resources/js/demo-guide.js.
--}}
<div data-demo-guide data-steps="{{ json_encode($steps) }}">
    {{-- Click catcher + dim layer for centred steps --}}
    <div data-guide-backdrop class="fixed inset-0 z-[60] hidden bg-white/70"></div>
    <div data-guide-highlight class="pointer-events-none fixed z-[61] hidden rounded-2xl shadow-[0_0_0_9999px_rgb(255_255_255/0.7)] outline-2 outline-offset-2 outline-demo-blue transition-all duration-300"></div>

    <div data-guide-card role="dialog" aria-live="polite" aria-labelledby="demo-guide-title" class="fixed z-[62] hidden max-h-[70vh] w-[min(22rem,calc(100vw-1.5rem))] overflow-y-auto rounded-2xl bg-white p-5 shadow-2xl ring-1 ring-slate-900/5 sm:p-6">
        <div class="flex items-center justify-between gap-2">
            <span data-guide-counter class="text-[11px] font-bold tracking-wider text-demo-blue uppercase"></span>
            <span data-guide-status class="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-demo-blue"></span>
        </div>
        <h3 id="demo-guide-title" data-guide-title class="mt-1 font-demo text-base font-bold text-demo-navy"></h3>
        <p data-guide-body class="mt-2 text-sm leading-relaxed text-slate-600"></p>
        <p data-guide-example class="mt-3 hidden rounded-xl border border-amber-200 bg-amber-50 px-3 py-2.5 text-xs leading-relaxed text-amber-900">
            <span class="font-bold"><x-demo.t en="Example:" tl="Halimbawa:" /></span>
            <span data-guide-example-text></span>
        </p>

        <div class="mt-4 h-1 overflow-hidden rounded-full bg-slate-100">
            <div data-guide-progress class="h-full w-0 rounded-full bg-demo-blue"></div>
        </div>

        <div data-guide-final class="mt-4 hidden flex-col gap-2">
            <a href="#" data-guide-next-module class="inline-flex items-center justify-center rounded-full bg-demo-navy px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-demo-navy/90"></a>
            <a href="{{ url('/') }}#demo" target="_top" data-demo-book class="bg-brand-gradient inline-flex items-center justify-center rounded-full px-5 py-2.5 text-sm font-semibold text-white shadow-sm">
                Book a Free Demo
                <span class="material-symbols-outlined ml-1.5 text-[16px]">arrow_forward</span>
            </a>
            <button type="button" data-guide-restart class="rounded-full bg-slate-100 px-5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200"><x-demo.t en="Replay tour" tl="Ulitin ang tour" /></button>
        </div>

        <div class="mt-5 flex items-center justify-between gap-3">
            <button type="button" data-guide-stop class="text-sm text-slate-500 underline underline-offset-2 hover:text-slate-800">End tour</button>
            <div class="flex items-center gap-2">
                <button type="button" data-guide-toggle aria-label="Pause tour" class="flex size-9 items-center justify-center rounded-full bg-slate-100 text-slate-700 hover:bg-slate-200">
                    <span data-guide-toggle-icon class="material-symbols-outlined text-[18px]">pause</span>
                </button>
                <button type="button" data-guide-back class="rounded-[10px] bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 disabled:cursor-not-allowed disabled:opacity-40">Back</button>
                <button type="button" data-guide-next class="rounded-[10px] bg-demo-blue px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"></button>
            </div>
        </div>
    </div>

    {{-- Section explanation (Explore dots, "i" badges). See-through so the section stays visible underneath --}}
    <div data-guide-tip role="tooltip" class="fixed z-[55] hidden w-[min(300px,calc(100vw-1.5rem))] rounded-xl bg-demo-navy/75 p-4 text-white shadow-xl ring-1 ring-white/10 backdrop-blur-[2px]">
        <div data-guide-tip-title class="font-demo text-sm font-bold"></div>
        <p data-guide-tip-body class="mt-1.5 text-xs leading-relaxed text-slate-100"></p>
        <p data-guide-tip-example class="mt-2 hidden rounded-lg bg-white/10 px-2.5 py-2 text-xs leading-relaxed text-amber-100">
            <span class="font-bold"><x-demo.t en="Example:" tl="Halimbawa:" /></span>
            <span data-guide-tip-example-text></span>
        </p>
        {{-- Only on tips opened from an "i" badge; Explore-dot tips show the explanation alone --}}
        <div data-guide-tip-actions class="mt-2.5 flex flex-wrap items-center gap-x-1.5 gap-y-1 text-xs">
            <button type="button" data-guide-tip-start class="font-semibold text-demo-mint hover:underline"><x-demo.t en="Start full guide →" tl="Simulan ang full guide →" /></button>
            <span class="text-slate-300">
                <x-demo.t en="or" tl="o" />
                <button type="button" data-guide-tip-explore class="font-semibold text-white underline decoration-white/40 underline-offset-2 hover:decoration-white"><x-demo.t en="explore on your own" tl="mag-explore mag-isa" /></button>
            </span>
        </div>
    </div>

    {{-- Explore mode: a glowing dot on every [data-tour] section; hover or tap a dot to read about it --}}
    <div data-guide-dots aria-label="Feature hotspots"></div>

    <template data-guide-dot-template>
        <button type="button" class="fixed z-[51] flex size-8 -translate-x-1/2 -translate-y-1/2 items-center justify-center">
            <span class="absolute inset-1.5 animate-hotspot-ripple rounded-full border-2 border-demo-blue"></span>
            <span class="absolute inset-1.5 animate-hotspot-ripple rounded-full border-2 border-demo-blue [animation-delay:0.8s]"></span>
            <span class="absolute inset-1.5 animate-hotspot-ripple rounded-full border-2 border-demo-blue [animation-delay:1.6s]"></span>
            <span class="relative size-4 animate-hotspot-glow rounded-full bg-demo-blue"></span>
        </button>
    </template>

    {{-- Entering the demo: a large chooser with a real screenshot of each mode (images captured from this demo) --}}
    <div data-guide-coach role="dialog" aria-modal="true" aria-labelledby="guide-coach-title" class="fixed inset-0 z-[85] hidden items-center justify-center bg-demo-navy/60 p-3 backdrop-blur-sm sm:p-6">
        <div class="relative max-h-[94dvh] w-full max-w-5xl overflow-y-auto rounded-3xl bg-white p-5 shadow-2xl sm:p-8 lg:w-[70vw]">
            <button type="button" data-guide-coach-close aria-label="Close and explore" class="absolute top-4 right-4 flex size-9 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:text-slate-800">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>

            <x-demo.lang-toggle class="mb-4 shadow-sm sm:absolute sm:top-4 sm:left-4 sm:mb-0" />

            <div class="pr-10 text-center sm:px-10">
                <span class="text-[11px] font-bold tracking-wider text-demo-blue uppercase">Live demo · Sample data</span>
                <h2 id="guide-coach-title" class="mt-1 font-demo text-2xl font-extrabold tracking-tight text-demo-navy sm:text-3xl"><x-demo.t en="Two ways to try ZionBuild" tl="Dalawang paraan para subukan ang ZionBuild" /></h2>
                <p class="mt-1.5 text-sm text-slate-500"><x-demo.t en="Pick how you want to look around. You can switch anytime with the buttons at the bottom-right." tl="Piliin kung paano mo gustong tumingin-tingin. Puwede kang lumipat anytime gamit ang buttons sa bandang baba-kanan." /></p>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2 md:gap-6">
                <button type="button" data-guide-coach-explore class="group flex flex-col rounded-2xl border-2 border-slate-200 p-3 text-left transition hover:-translate-y-0.5 hover:border-demo-blue hover:shadow-xl sm:p-4">
                    <span class="relative overflow-hidden rounded-xl border border-slate-200">
                        <img src="{{ asset('images/demo/preview-explore.jpg') }}" alt="Explore mode: glowing dots on each section of the Command Center" class="aspect-[16/10] w-full object-cover object-top">
                        <span class="absolute top-2 left-2 rounded-full bg-demo-mint px-2 py-0.5 text-[10px] font-extrabold tracking-wide text-demo-green uppercase">
                            <x-demo.t en="Free to roam" tl="Malayang tumingin" />
                        </span>
                    </span>
                    <span class="mt-4 flex items-center gap-2 font-demo text-lg font-extrabold text-demo-navy">
                        <span class="flex size-8 items-center justify-center rounded-full border-2 border-demo-blue text-demo-blue"><span class="material-symbols-outlined text-[18px]">explore</span></span>
                        Explore
                    </span>
                    <span class="mt-2 text-sm text-slate-600">
                        <x-demo.t>
                            <x-slot:en>Look around on your own. <strong class="text-demo-navy">Glowing dots</strong> mark the key parts of each screen. <span class="hidden pointer-fine:inline">Hover over</span><span class="pointer-fine:hidden">Tap</span> one to see what it does.</x-slot:en>
                            <x-slot:tl>Tumingin-tingin mag-isa. <strong class="text-demo-navy">Glowing dots</strong> ang nagtuturo sa mahahalagang parte ng bawat screen. <span class="hidden pointer-fine:inline">I-hover</span><span class="pointer-fine:hidden">I-tap</span> ang isa para malaman kung para saan ito.</x-slot:tl>
                        </x-demo.t>
                    </span>
                    <span class="mt-auto pt-4">
                        <span class="flex w-full items-center justify-center gap-1.5 rounded-full border-2 border-demo-blue py-2.5 font-demo text-sm font-bold text-demo-blue transition group-hover:bg-demo-blue group-hover:text-white">
                            <x-demo.t en="Start exploring" tl="Simulan ang pag-explore" />
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </span>
                    </span>
                </button>

                <button type="button" data-guide-coach-guide class="group flex flex-col rounded-2xl border-2 border-slate-200 p-3 text-left transition hover:-translate-y-0.5 hover:border-demo-blue hover:shadow-xl sm:p-4">
                    <span class="relative overflow-hidden rounded-xl border border-slate-200">
                        <img src="{{ asset('images/demo/preview-guide.jpg') }}" alt="Guide mode: a step-by-step tour card explaining Net Cash Position" class="aspect-[16/10] w-full object-cover object-top">
                        <span class="absolute top-2 left-2 rounded-full bg-amber-300 px-2 py-0.5 text-[10px] font-extrabold tracking-wide text-amber-900 uppercase">
                            <x-demo.t en="Under 2 minutes" tl="Wala pang 2 minuto" />
                        </span>
                    </span>
                    <span class="mt-4 flex items-center gap-2 font-demo text-lg font-extrabold text-demo-navy">
                        <span class="flex size-8 items-center justify-center rounded-full bg-demo-blue text-amber-300"><span class="material-symbols-outlined text-[18px]">lightbulb</span></span>
                        Guide
                    </span>
                    <span class="mt-2 text-sm text-slate-600">
                        <x-demo.t>
                            <x-slot:en>A short tour that <strong class="text-demo-navy">walks you through each feature</strong>, step by step, with real examples. It plays on its own; pause or skip anytime.</x-slot:en>
                            <x-slot:tl>Maikling tour na <strong class="text-demo-navy">isa-isang ipapakita ang bawat feature</strong>, may kasamang halimbawa. Kusa itong tumatakbo; puwede mong i-pause o i-skip anytime.</x-slot:tl>
                        </x-demo.t>
                    </span>
                    <span class="mt-auto pt-4">
                        <span class="flex w-full items-center justify-center gap-1.5 rounded-full border-2 border-demo-blue bg-demo-blue py-2.5 font-demo text-sm font-bold text-white shadow-lg shadow-demo-blue/30 transition group-hover:bg-blue-700">
                            <x-demo.t en="Start the guided tour" tl="Simulan ang guided tour" />
                            <span class="material-symbols-outlined text-[18px]">play_arrow</span>
                        </span>
                    </span>
                </button>
            </div>

            <p class="mt-5 text-center text-xs text-slate-400"><x-demo.t en="Everything in this demo is sample data. Nothing you do is saved or sent." tl="Sample data lang lahat sa demo na ito. Walang nase-save o naipapadala." /></p>
        </div>
    </div>

    {{-- Shown the first time Explore is turned on --}}
    <div data-guide-explore-intro role="dialog" aria-modal="true" aria-labelledby="explore-intro-title" class="fixed inset-0 z-[80] hidden items-center justify-center bg-demo-navy/40 p-4 backdrop-blur-md">
        <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl sm:p-8">
            <span class="flex size-12 items-center justify-center rounded-2xl bg-blue-50 text-demo-blue">
                <span class="material-symbols-outlined text-[28px]">explore</span>
            </span>
            <h2 id="explore-intro-title" class="mt-4 font-demo text-xl font-extrabold tracking-tight text-demo-navy"><x-demo.t en="Explore on your own" tl="Mag-explore mag-isa" /></h2>
            <p class="mt-1 text-sm text-slate-500"><x-demo.t en="No walkthrough, just look around at your own pace." tl="Walang walkthrough, tingin-tingin lang sa sarili mong bilis." /></p>

            <ul class="mt-5 space-y-3.5 text-sm text-slate-700">
                <li class="flex gap-3">
                    <span class="relative mt-1 flex size-5 shrink-0 items-center justify-center">
                        <span class="absolute inset-0.5 animate-hotspot-ripple rounded-full border-2 border-demo-blue"></span>
                        <span class="relative size-3 animate-hotspot-glow rounded-full bg-demo-blue"></span>
                    </span>
                    <span><x-demo.t>
                        <x-slot:en><strong class="text-demo-navy">Glowing dots</strong> mark the key parts of each screen. <span class="hidden pointer-fine:inline">Hover over</span><span class="pointer-fine:hidden">Tap</span> a dot to see what it does, with an example.</x-slot:en>
                        <x-slot:tl><strong class="text-demo-navy">Glowing dots</strong> ang nagtuturo sa mahahalagang parte ng bawat screen. <span class="hidden pointer-fine:inline">I-hover</span><span class="pointer-fine:hidden">I-tap</span> ang dot para malaman kung para saan ito, may kasamang halimbawa.</x-slot:tl>
                    </x-demo.t></span>
                </li>
                <li class="flex gap-3">
                    <span class="material-symbols-outlined shrink-0 text-[20px] text-demo-blue">touch_app</span>
                    <span><x-demo.t>
                        <x-slot:en><strong class="text-demo-navy">Click around freely.</strong> Switch tabs, open forms and try the buttons.</x-slot:en>
                        <x-slot:tl><strong class="text-demo-navy">Malaya kang mag-click.</strong> Lumipat ng tabs, buksan ang forms at subukan ang buttons.</x-slot:tl>
                    </x-demo.t></span>
                </li>
                <li class="flex gap-3">
                    <span class="material-symbols-outlined shrink-0 text-[20px] text-demo-blue">database</span>
                    <span><x-demo.t>
                        <x-slot:en><strong class="text-demo-navy">Everything is sample data.</strong> The project, names, numbers and photos are made up, and nothing you do is saved or sent.</x-slot:en>
                        <x-slot:tl><strong class="text-demo-navy">Sample data lang lahat.</strong> Gawa-gawa lang ang project, mga pangalan, numbers at photos, at walang nase-save o naipapadala.</x-slot:tl>
                    </x-demo.t></span>
                </li>
                <li class="flex gap-3">
                    <span class="material-symbols-outlined shrink-0 text-[20px] text-amber-500">lightbulb</span>
                    <span><x-demo.t>
                        <x-slot:en><strong class="text-demo-navy">Prefer a walkthrough?</strong> Press Guide anytime for a step-by-step tour.</x-slot:en>
                        <x-slot:tl><strong class="text-demo-navy">Mas gusto ng walkthrough?</strong> Pindutin ang Guide anytime para sa step-by-step tour.</x-slot:tl>
                    </x-demo.t></span>
                </li>
            </ul>

            <div class="mt-7 flex flex-col gap-2">
                <button type="button" data-guide-explore-go class="rounded-full bg-demo-blue px-5 py-3 font-demo text-sm font-bold text-white shadow-lg shadow-demo-blue/30 transition hover:bg-blue-700"><x-demo.t en="Start exploring" tl="Simulan ang pag-explore" /></button>
                <button type="button" data-guide-explore-tour class="rounded-full px-5 py-2 text-sm font-semibold text-slate-500 hover:text-demo-navy"><x-demo.t en="Take the guided tour instead" tl="Guided tour na lang" /></button>
            </div>
        </div>
    </div>

    <div data-guide-launchers class="fixed right-4 bottom-4 z-50 flex flex-col items-end gap-2 sm:right-6 sm:bottom-6">
        <div class="flex flex-col items-end gap-2 sm:flex-row sm:items-center">
            <x-demo.lang-toggle class="shadow-xl" />
            <div class="flex items-center gap-2">
                <button type="button" data-guide-explore aria-pressed="false" class="group inline-flex items-center gap-2 rounded-full border-2 border-demo-blue bg-white px-4 py-2 font-demo text-sm font-bold text-demo-blue shadow-xl transition hover:scale-105 active:scale-95 aria-pressed:border-red-600 aria-pressed:bg-red-600 aria-pressed:text-white aria-pressed:hover:bg-red-700">
                    {{-- Wrapped: the icon font's own display rule would override hidden on the icon itself --}}
                    <span class="flex group-aria-pressed:hidden"><span class="material-symbols-outlined text-[18px]">explore</span></span>
                    <span class="hidden group-aria-pressed:flex"><span class="material-symbols-outlined text-[18px]">close</span></span>
                    <span class="group-aria-pressed:hidden">Explore</span>
                    <span class="hidden group-aria-pressed:inline"><x-demo.t en="Done exploring" tl="Tapos na" /></span>
                </button>
                <button type="button" data-guide-launcher class="inline-flex items-center gap-2 rounded-full bg-demo-blue px-4 py-2.5 font-demo text-sm font-bold text-white shadow-xl shadow-demo-blue/30 transition hover:scale-105 hover:bg-blue-700 active:scale-95">
                    <span class="material-symbols-outlined text-[18px] text-amber-300">lightbulb</span>
                    Guide
                </button>
            </div>
        </div>
    </div>
</div>
