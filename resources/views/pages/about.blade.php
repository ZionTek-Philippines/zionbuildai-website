@php
    $values = [
        ['icon' => 'diversity_3', 'title' => 'Technology serves people', 'body' => 'Systems should make work easier for the people using them, not the other way around.'],
        ['icon' => 'tune', 'title' => 'Purpose-built, not one-size-fits-all', 'body' => 'We fit software to how your business actually works instead of forcing you to change your processes.'],
        ['icon' => 'verified', 'title' => 'Integrity is non-negotiable', 'body' => 'We are honest about what we build, what it can do, and what it costs.'],
        ['icon' => 'handshake', 'title' => 'Your project is our project', 'body' => 'Every project gets our priority, professionalism and purpose, as if it were our own.'],
    ];

    $ecosystem = [
        ['icon' => 'code_blocks', 'name' => 'ZionTek', 'body' => 'Custom websites, e-commerce, and ERP systems: accounting, payroll, inventory, HRIS and manufacturing.', 'current' => false],
        ['icon' => 'foundation', 'name' => 'ZionBuild AI', 'body' => 'Construction management that keeps contractors, teams and homeowners in sync, from groundbreaking to handover.', 'current' => true],
        ['icon' => 'real_estate_agent', 'name' => 'ZionProperties', 'body' => 'A verified real estate solution for lot buying, title processing and ownership.', 'current' => false],
    ];

    $team = [
        ['name' => 'Regie Padilla Ebalobor', 'role' => 'Founder & Chief Technology Officer'],
        ['name' => 'Evelyn Ebalobor', 'role' => 'Chief Financial Officer'],
        ['name' => 'Andrea Jimenez', 'role' => 'System Analyst'],
        ['name' => 'Yuki Refugio', 'role' => 'Business Analyst'],
        ['name' => 'Janus Jordan', 'role' => 'Lead, Web & Mobile Development'],
        ['name' => 'John Paul de Jesus', 'role' => 'Lead, Web Development'],
        ['name' => 'John Ezekiel Macairan', 'role' => 'Social Media Analyst'],
    ];

    $mask = fn (string $text): string => base64_encode(implode(array_reverse(mb_str_split($text))));
    $initials = fn (string $name): string => mb_substr($name, 0, 1).mb_strtoupper(mb_substr(str($name)->afterLast(' '), 0, 1));
@endphp

<x-landing.page title="About Us" robots="noindex, nofollow, noarchive, nosnippet" description="ZionBuild AI is built by ZionTek, a Philippine software team with over 15 years of experience building custom systems, ERP, mobile apps and AI-powered business platforms.">
    <section class="relative overflow-hidden bg-surface py-16 lg:py-24">
        <div class="pointer-events-none absolute -top-24 left-1/2 h-[500px] w-[900px] -translate-x-1/2 rounded-full bg-primary/10 blur-3xl"></div>
        <div class="relative mx-auto max-w-4xl px-margin-sm text-center lg:px-margin">
            <span class="inline-flex items-center gap-2 rounded-full bg-surface-container-high px-4 py-1.5 font-label-md text-label-md text-primary shadow-xs">
                <span class="material-symbols-outlined text-[18px]">favorite</span>
                About ZionTek
            </span>
            <h1 class="mt-6 font-display-hero text-display-hero-mobile font-extrabold tracking-tight text-on-surface sm:text-display-hero">
                Building better lives through <span class="text-primary">smarter systems.</span>
            </h1>
            <p class="mx-auto mt-5 max-w-2xl font-body-lg text-body-lg text-on-surface-variant">
                ZionBuild AI is made by ZionTek, a Philippine software team with <strong class="text-on-surface">over 15 years of experience</strong> in system development, mobile apps, and web and e-commerce development. We build purpose-made software that fits how businesses actually work.
            </p>
        </div>
    </section>

    <section class="bg-surface-container-low py-20">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-margin-sm lg:grid-cols-2 lg:px-margin">
            <div class="space-y-5">
                <p class="font-label-md text-label-md font-bold tracking-widest text-primary uppercase">Why we built ZionBuild</p>
                <h2 class="font-headline-lg text-headline-lg font-extrabold text-on-surface">Construction is hard enough. Managing it shouldn't be.</h2>
                <p class="font-body-lg text-body-lg text-on-surface-variant">
                    After years of building ERP, accounting, payroll and inventory systems for businesses, including in real estate and construction, we kept seeing the same thing on job sites: progress reported through chat messages, attendance on paper, and budgets in spreadsheets that are already outdated.
                </p>
                <p class="font-body-lg text-body-lg text-on-surface-variant">
                    ZionBuild AI brings all of it into one system, so contractors know what's happening on every site, teams spend less time on paperwork, and homeowners stay informed without constant site visits.
                </p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                @foreach ([['15+', 'years building business systems'], ['1', 'system for contractors, teams and homeowners'], ['PH', 'built for Philippine builders'], ['100%', 'human-approved AI suggestions']] as [$figure, $label])
                    <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-xs">
                        <p class="font-headline-lg text-headline-lg font-extrabold text-primary">{{ $figure }}</p>
                        <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">{{ $label }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-surface py-20">
        <div class="mx-auto max-w-7xl px-margin-sm lg:px-margin">
            <div class="mx-auto max-w-2xl text-center">
                <p class="font-label-md text-label-md font-bold tracking-widest text-primary uppercase">What we believe</p>
                <h2 class="mt-2 font-headline-lg text-headline-lg font-extrabold text-on-surface">We listen, we care, and we deliver.</h2>
            </div>
            <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($values as $value)
                    <div class="rounded-2xl bg-surface-container-low p-6">
                        <span class="flex size-11 items-center justify-center rounded-xl bg-primary-fixed text-primary">
                            <span class="material-symbols-outlined text-[24px]">{{ $value['icon'] }}</span>
                        </span>
                        <h3 class="mt-4 font-title-md text-title-md font-bold text-on-surface">{{ $value['title'] }}</h3>
                        <p class="mt-2 font-body-md text-body-md text-on-surface-variant">{{ $value['body'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-surface-container-low py-20">
        <div class="mx-auto max-w-7xl px-margin-sm lg:px-margin">
            <div class="mx-auto max-w-2xl text-center">
                <p class="font-label-md text-label-md font-bold tracking-widest text-primary uppercase">The ZionTek ecosystem</p>
                <h2 class="mt-2 font-headline-lg text-headline-lg font-extrabold text-on-surface">One team, three platforms.</h2>
            </div>
            <div class="mt-12 grid gap-5 md:grid-cols-3">
                @foreach ($ecosystem as $product)
                    <div @class([
                        'rounded-2xl p-7',
                        'bg-brand-gradient text-on-primary shadow-xl' => $product['current'],
                        'bg-surface-container-lowest shadow-xs' => ! $product['current'],
                    ])>
                        <div class="flex items-center justify-between">
                            <span @class([
                                'flex size-12 items-center justify-center rounded-2xl',
                                'bg-white/20 text-on-primary' => $product['current'],
                                'bg-primary-fixed text-primary' => ! $product['current'],
                            ])>
                                <span class="material-symbols-outlined text-[26px]">{{ $product['icon'] }}</span>
                            </span>
                            @if ($product['current'])
                                <span class="rounded-full bg-white/20 px-3 py-1 font-label-sm text-label-sm font-semibold">You are here</span>
                            @endif
                        </div>
                        <h3 @class(['mt-5 font-headline-md text-xl font-extrabold', 'text-on-surface' => ! $product['current']])>{{ $product['name'] }}</h3>
                        <p @class(['mt-2 font-body-md text-body-md', 'text-primary-fixed' => $product['current'], 'text-on-surface-variant' => ! $product['current']])>{{ $product['body'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-surface py-20">
        <div class="mx-auto max-w-7xl px-margin-sm lg:px-margin">
            <div class="mx-auto max-w-2xl text-center">
                <p class="font-label-md text-label-md font-bold tracking-widest text-primary uppercase">Our team</p>
                <h2 class="mt-2 font-headline-lg text-headline-lg font-extrabold text-on-surface">The people behind ZionBuild.</h2>
                <p class="mt-3 font-body-lg text-body-lg text-on-surface-variant">Software engineers across mobile, web and backend, working side by side with systems and business analysts.</p>
            </div>
            <div class="mt-12 grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
                {{-- Names and initials ship encoded and are decoded in the browser (resources/js/masked-text.js) so they never appear in the HTML that bots read --}}
                @foreach ($team as $member)
                    <div class="rounded-2xl bg-surface-container-low p-6 text-center">
                        <span data-masked="{{ $mask($initials($member['name'])) }}" class="bg-brand-gradient mx-auto flex size-16 items-center justify-center rounded-full font-headline-md text-xl font-extrabold text-on-primary"></span>
                        <p data-masked="{{ $mask($member['name']) }}" class="mt-4 min-h-7 font-title-md text-title-md font-bold text-on-surface"></p>
                        <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">{{ $member['role'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-surface pb-20">
        <div class="mx-auto max-w-7xl px-margin-sm lg:px-margin">
            <div class="bg-brand-gradient rounded-3xl p-10 text-center text-on-primary shadow-2xl lg:p-14">
                <h2 class="font-headline-lg text-headline-lg font-extrabold">Let's build something that fits your business.</h2>
                <p class="mx-auto mt-3 max-w-xl font-body-lg text-body-lg text-primary-fixed">See ZionBuild AI with your own projects, or talk to us about a custom system.</p>
                <div class="mt-8 flex flex-col items-center justify-center gap-4 sm:flex-row">
                    <a href="{{ url('/') }}#demo" class="inline-flex w-full items-center justify-center rounded-full bg-surface-container-lowest px-8 py-3.5 font-label-md text-label-md font-bold text-primary shadow-xl transition-all hover:bg-surface-container-low sm:w-auto">
                        Book a Free Demo
                        <span class="material-symbols-outlined ml-2 text-[18px]">calendar_month</span>
                    </a>
                    <a href="{{ route('contact') }}" class="inline-flex w-full items-center justify-center rounded-full bg-white/15 px-8 py-3.5 font-label-md text-label-md font-bold text-on-primary ring-1 ring-white/30 transition-all hover:bg-white/25 sm:w-auto">
                        Contact Us
                        <span class="material-symbols-outlined ml-2 text-[18px]">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-landing.page>
