@props(['title', 'active', 'activeIcon' => 'home'])

@php
    $modules = [
        ['key' => 'how-it-works', 'label' => 'How It Works', 'route' => 'demo.how-it-works'],
        ['key' => 'command-center', 'label' => 'Command Center', 'route' => 'demo.command-center'],
        ['key' => 'field-reports', 'label' => 'Field Reports', 'route' => 'demo.field-reports'],
        ['key' => 'ai-project-monitoring', 'label' => 'AI Project Monitoring', 'route' => 'demo.ai-project-monitoring'],
        ['key' => 'finances', 'label' => 'Finances', 'route' => 'demo.finances', 'gated' => true],
        ['key' => 'payroll', 'label' => 'Payroll', 'route' => 'demo.payroll', 'gated' => true],
        ['key' => 'client-billing', 'label' => 'Client Billing', 'route' => 'demo.client-billing', 'gated' => true],
    ];
    $isUnlocked = (bool) session(\App\Http\Middleware\EnsureDemoIsUnlocked::SESSION_KEY);
    $nextModuleKey = $modules[array_search($active, array_column($modules, 'key')) + 1]['key'] ?? null;

    $sidebarGroups = [
        ['home', 'bolt', 'rocket_launch', 'videocam'],
        ['bar_chart', 'receipt', 'flag', 'photo_camera', 'menu_book'],
        ['account_balance_wallet', 'payments', 'account_balance', 'swap_horiz'],
        ['groups', 'list', 'receipt_long'],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex">

        <title>{{ $title }} — ZionBuild AI Demo</title>
        <meta name="description" content="Try ZionBuild AI with sample data: a guided tour of the construction Command Center.">
        @guest
            <meta name="site-analytics" content="{{ route('site-events.store') }}">
        @endguest

        @fonts

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block">

        {{-- Apply the saved language (or ?lang=tl) before first paint; resources/js/demo-lang.js takes over from here --}}
        <script>
            try {
                const lang = new URLSearchParams(location.search).get('lang') ?? localStorage.getItem('zionbuild-demo-lang');
                if (lang === 'tl') document.documentElement.dataset.demoLang = 'tl';
            } catch (error) {}
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-linear-135 from-[#f6fafe] to-[#ece6fb] font-sans text-slate-800 antialiased">
        {{-- Demo frame --}}
        <div class="sticky top-0 z-50 flex h-11 items-center gap-3 bg-linear-to-r from-red-700 via-red-600 to-rose-600 px-3 text-xs text-white shadow-md sm:px-6">
            <div class="flex shrink-0 items-center gap-2 font-medium">
                <span class="size-2 animate-pulse rounded-full bg-white"></span>
                <span class="font-semibold tracking-wide text-white">DEMO</span>
                <span class="hidden text-red-100 xl:inline">· Sample data only</span>
            </div>

            <nav data-tour="module-menu" class="flex min-w-0 flex-1 items-center gap-1 overflow-x-auto py-1.5 [scrollbar-width:none] lg:justify-center-safe" aria-label="Demo modules">
                @foreach ($modules as $module)
                    @if ($module['key'] === $active)
                        <a href="{{ route($module['route']) }}" aria-current="page" class="shrink-0 rounded-full bg-white px-3.5 py-1 font-semibold text-red-700 shadow-sm">{{ $module['label'] }}</a>
                    @elseif ($module['key'] === $nextModuleKey)
                        {{-- Teaches visitors to walk the menu one module at a time --}}
                        <a href="{{ route($module['route']) }}" data-demo-next class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-amber-300 py-1 pr-3 pl-1 font-semibold text-red-900 shadow-md ring-2 ring-amber-100 transition hover:bg-amber-200">
                            <span class="inline-flex items-center gap-0.5 rounded-full bg-red-700 px-2 py-0.5 text-[10px] font-bold tracking-wide text-white uppercase">
                                <span class="material-symbols-outlined animate-bounce text-[13px] motion-reduce:animate-none">touch_app</span>
                                <span class="hidden sm:inline"><x-demo.t en="Tap next" tl="Susunod" /></span>
                            </span>
                            {{ $module['label'] }}
                            @if (($module['gated'] ?? false) && ! $isUnlocked)
                                <span class="flex" title="Unlock with a short form"><span class="material-symbols-outlined text-[13px]">lock</span></span>
                            @endif
                        </a>
                    @else
                        <a href="{{ route($module['route']) }}" @class([
                            'inline-flex shrink-0 items-center gap-1 rounded-full bg-white/15 px-3 py-1 font-medium text-white ring-1 ring-white/25 transition',
                            'hover:bg-black/40 hover:ring-black/20' => ($module['gated'] ?? false) && ! $isUnlocked,
                            'hover:bg-white/30' => ! (($module['gated'] ?? false) && ! $isUnlocked),
                        ])>
                            {{ $module['label'] }}
                            @if (($module['gated'] ?? false) && ! $isUnlocked)
                                <span class="flex" title="Unlock with a short form"><span class="material-symbols-outlined text-[13px] text-red-100">lock</span></span>
                            @endif
                        </a>
                    @endif
                @endforeach
            </nav>

            <div class="flex shrink-0 items-center gap-2 sm:gap-3">
                <a href="{{ url('/') }}#demo" target="_top" data-demo-book class="inline-flex items-center gap-1.5 rounded-full bg-linear-to-r from-blue-600 to-indigo-600 px-3 py-1 font-semibold text-white shadow-sm transition hover:from-blue-500 hover:to-indigo-500">
                    <span class="hidden sm:inline">Book a Free Demo</span>
                    <span class="sm:hidden">Book Demo</span>
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
                <a href="{{ url('/') }}" target="_top" data-demo-close aria-label="Close demo" class="p-0.5 text-white/80 transition hover:text-white">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </a>
            </div>
        </div>

        {{-- App top bar --}}
        <div class="sticky top-11 z-40 flex h-16 items-center justify-between gap-3 bg-linear-to-r from-demo-indigo to-demo-violet px-3 text-white shadow-lg sm:px-6">
            <div class="flex min-w-0 items-center gap-3 sm:gap-6">
                <span class="font-demo text-xl font-extrabold tracking-tight sm:text-2xl">zionbuild</span>
                <span data-tour="project-switcher" class="inline-flex min-w-0 items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-sm font-semibold backdrop-blur-md sm:px-4">
                    <span class="material-symbols-outlined text-[18px] text-amber-300">home_work</span>
                    <span class="truncate tracking-tight">Ong Residential</span>
                    <span class="material-symbols-outlined text-[16px] text-white/70">swap_horiz</span>
                </span>
            </div>

            <div class="flex shrink-0 items-center gap-3">
                <span class="relative hidden p-2 text-white/90 sm:inline-flex">
                    <span class="material-symbols-outlined text-[22px]">notifications</span>
                    <span class="absolute top-1.5 right-1.5 flex size-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold">1</span>
                </span>
                <div class="hidden items-center gap-2 border-l border-white/20 pl-4 md:flex">
                    <span class="flex size-8 items-center justify-center rounded-full bg-blue-500 shadow"><span class="material-symbols-outlined text-[18px]">group</span></span>
                    <span class="flex size-8 items-center justify-center rounded-full bg-rose-500 shadow"><span class="material-symbols-outlined text-[18px]">photo_camera</span></span>
                    <span class="flex size-8 items-center justify-center rounded-full bg-emerald-500 shadow"><span class="material-symbols-outlined text-[18px]">content_copy</span></span>
                    <span class="flex size-8 items-center justify-center rounded-full bg-white/25 shadow"><span class="material-symbols-outlined text-[20px]">add</span></span>
                </div>
                <span class="flex size-9 items-center justify-center rounded-full border-2 border-white/30 bg-demo-navy text-sm font-bold shadow-md">D</span>
            </div>
        </div>

        <div class="flex">
            {{-- Sidebar (decorative in the demo) --}}
            <aside class="hidden w-[72px] shrink-0 flex-col items-center border-r border-slate-200/80 bg-white py-4 shadow-sm lg:flex" aria-hidden="true">
                <div class="mb-5 flex size-10 items-center justify-center rounded-xl bg-demo-navy font-demo text-sm font-extrabold tracking-wider text-white shadow">ZB</div>
                @foreach ($sidebarGroups as $icons)
                    @unless ($loop->first)
                        <div class="my-1.5 h-px w-8 bg-slate-200"></div>
                    @endunless
                    <div class="flex flex-col items-center gap-1.5">
                        @foreach ($icons as $icon)
                            <span @class([
                                'flex size-11 items-center justify-center rounded-xl',
                                'bg-blue-50 text-demo-blue' => $icon === $activeIcon,
                                'text-slate-400' => $icon !== $activeIcon,
                            ])>
                                <span class="material-symbols-outlined text-[22px]">{{ $icon }}</span>
                            </span>
                        @endforeach
                    </div>
                @endforeach
            </aside>

            <main class="min-w-0 flex-1 px-4 py-6 sm:px-8 sm:py-8">
                <div class="mx-auto max-w-[1200px]">
                    {{ $slot }}

                    <footer class="mt-10 border-t border-slate-200/60 py-8 text-center">
                        <p class="text-[11px] font-bold tracking-widest text-slate-400 uppercase">ZionBuild © {{ now()->year }} · Crafted by ZionTek IT Solutions Labs</p>
                        <p class="mt-1 text-[11px] text-slate-400">All names, figures and photos in this demo are sample data.</p>
                    </footer>
                </div>
            </main>
        </div>

        <div data-demo-toast role="status" aria-live="polite" class="pointer-events-none fixed top-32 left-1/2 z-[90] hidden w-[min(26rem,calc(100vw-2rem))] -translate-x-1/2 rounded-xl bg-demo-navy px-4 py-3 text-sm text-white shadow-2xl"></div>

        {{ $guide ?? '' }}
    </body>
</html>
