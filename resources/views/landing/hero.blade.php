<section data-track-section="Hero" class="relative w-full overflow-hidden bg-surface py-16 lg:py-24">
    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
        <div class="absolute -top-40 left-1/2 h-[560px] w-[1000px] -translate-x-1/2 rounded-full bg-indigo-500/30 blur-[120px]"></div>
        <div class="absolute top-10 -left-32 size-[480px] animate-glow-drift rounded-full bg-violet-500/30 blur-[110px] motion-reduce:animate-none"></div>
        <div class="absolute top-32 -right-32 size-[460px] animate-glow-drift rounded-full bg-emerald-400/25 blur-[110px] [animation-delay:-7s] motion-reduce:animate-none"></div>
        <div class="absolute top-[55%] left-1/2 h-[420px] w-[900px] -translate-x-1/2 rounded-full bg-fuchsia-400/20 blur-[130px]"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-7xl px-margin-sm lg:px-margin">
        <div class="mx-auto flex max-w-4xl flex-col items-center space-y-space-md text-center">
            <div class="inline-flex items-center gap-space-xs rounded-full bg-surface-container-high px-4 py-1.5 font-label-md text-label-md text-primary shadow-xs">
                <span class="size-2 animate-pulse rounded-full bg-primary"></span>
                Manage Your Construction Projects From Anywhere
            </div>

            <h1 class="max-w-3xl font-display-hero text-display-hero-mobile font-extrabold tracking-tight text-on-surface sm:text-display-hero">
                ZionBuild, an <span class="text-primary">All-in-One Construction System.</span>
            </h1>

            <p class="max-w-3xl font-body-lg text-body-lg text-on-surface-variant">
                Daily progress photos. Worker attendance. Payroll, Vale and Cash Advances. Budget and Expense Tracking. Inventory &amp; Procurement. Project Milestones, S-Curve Monitoring. Homeowner App.
            </p>

            <div class="flex w-full flex-col items-center gap-space-md pt-space-sm sm:w-auto sm:flex-row">
                <a href="#demo" class="bg-brand-gradient inline-flex w-full items-center justify-center rounded-full px-8 py-3.5 font-label-md text-label-md text-on-primary shadow-md transition-all hover:opacity-95 sm:w-auto">
                    Book a Free Demo
                    <span class="material-symbols-outlined ml-2 text-[18px]">arrow_forward</span>
                </a>
                @demo
                    <a href="{{ route('demo.how-it-works') }}" data-try-it class="bg-success-gradient inline-flex w-full items-center justify-center rounded-full px-8 py-3.5 font-label-md text-label-md text-white shadow-md shadow-emerald-500/30 transition-all hover:opacity-95 sm:w-auto">
                        <span class="material-symbols-outlined mr-2 text-[18px]">play_circle</span>
                        Try it! Live Demo
                    </a>
                @enddemo
                <a href="#features" class="inline-flex w-full items-center justify-center rounded-full bg-surface-container-lowest px-8 py-3.5 font-label-md text-label-md text-on-surface shadow-xs transition-colors hover:bg-surface-container-low sm:w-auto">
                    Explore Features
                    <span class="material-symbols-outlined ml-2 text-[18px]">expand_more</span>
                </a>
            </div>
        </div>

        {{-- The real demo Command Center (public/images/hero-command-center.jpg, captured from /demo); clicking it opens the live demo unless DEMO_DISABLED --}}
        <a @demo href="{{ route('demo.how-it-works') }}" data-try-it aria-label="Try the live demo" @enddemo class="group relative mt-12 block lg:mt-16">
            <div class="overflow-hidden rounded-2xl bg-surface-container-lowest shadow-2xl ring-1 ring-on-surface/5">
                <div class="flex items-center gap-2 bg-surface-container-low px-4 py-3">
                    <span class="size-3 rounded-full bg-error"></span>
                    <span class="size-3 rounded-full bg-secondary-container"></span>
                    <span class="size-3 rounded-full bg-tertiary-fixed-dim"></span>
                    <span class="ml-4 hidden rounded-full bg-surface-container-lowest px-4 py-1 font-data-mono text-data-mono text-on-surface-variant sm:inline">zionbuild.ai · Command Center</span>
                </div>
                <img src="{{ asset('images/hero-command-center.jpg') }}" alt="ZionBuild Command Center: contract price, project cost, earnings, net cash, client payments, payroll and expenses for one project" width="2160" height="1410" class="w-full">
            </div>

            @demo
                <span class="pointer-events-none absolute inset-x-0 bottom-0 h-3/5 rounded-b-2xl bg-linear-to-t from-surface via-surface/70 to-transparent"></span>

                <span class="absolute inset-x-0 bottom-8 flex justify-center sm:bottom-12">
                    <span class="relative inline-flex">
                        <span class="absolute inset-0 animate-ping rounded-full bg-emerald-500/40 motion-reduce:hidden"></span>
                        <span class="bg-success-gradient relative inline-flex items-center rounded-full px-8 py-4 font-label-md text-base font-semibold text-white shadow-xl shadow-emerald-600/40 ring-4 ring-white/70 transition-transform group-hover:scale-105 sm:px-10 sm:text-lg">
                            <span class="material-symbols-outlined mr-2 text-[22px]">play_circle</span>
                            Try it! Live Demo
                        </span>
                    </span>
                </span>
            @enddemo
        </a>
    </div>
</section>
