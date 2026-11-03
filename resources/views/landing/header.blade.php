<header data-track-section="Header" class="fixed inset-x-0 top-0 z-50 bg-surface-container-lowest/90 shadow-[0_1px_8px_rgba(0,0,0,0.04)] backdrop-blur-md">
    <div class="mx-auto flex h-20 max-w-7xl items-center justify-between gap-space-lg px-margin-sm lg:px-margin">
        <div class="flex shrink-0 items-center gap-space-md">
            <a href="{{ url('/') }}" aria-label="ZionBuild AI home">
                @include('landing.logo')
            </a>
            <span class="hidden items-center rounded-full bg-surface-container px-space-sm py-space-xs font-label-sm text-label-sm text-on-surface-variant sm:inline-flex">PH</span>
        </div>

        <nav class="hidden items-center gap-space-lg md:flex">
            <a href="{{ url('/') }}#features" class="font-body-md text-body-md text-on-surface-variant transition-colors hover:text-on-surface">Features</a>
            <a href="{{ url('/') }}#how-it-works" class="font-body-md text-body-md text-on-surface-variant transition-colors hover:text-on-surface">How It Works</a>
            <a href="{{ url('/') }}#faq" class="font-body-md text-body-md text-on-surface-variant transition-colors hover:text-on-surface">FAQ</a>
        </nav>

        <div class="flex shrink-0 items-center gap-space-md">
            <a href="{{ url('/') }}#features" class="hidden items-center px-space-sm py-space-xs font-body-md text-body-md text-on-surface-variant transition-colors hover:text-on-surface lg:inline-flex">Explore Features</a>
            <a href="{{ route('demo.how-it-works') }}" data-try-it class="bg-success-gradient hidden items-center gap-1 rounded-full px-4 py-2.5 font-label-md text-label-md text-white shadow-xs transition-opacity hover:opacity-95 sm:inline-flex">
                <span class="material-symbols-outlined text-[18px]">play_circle</span>
                Try it!
            </a>
            <a href="{{ url('/') }}#demo" class="bg-brand-gradient inline-flex items-center justify-center rounded-full px-5 py-2.5 font-label-md text-label-md text-on-primary shadow-xs transition-opacity hover:opacity-95">Book a Free Demo</a>
            <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-primary">
                <span class="material-symbols-outlined text-[18px] text-on-primary">person</span>
            </div>
        </div>
    </div>
</header>
