@php
    $channels = [
        ['icon' => 'call', 'label' => 'Call or text us', 'value' => '+63 961 785 8547', 'href' => 'tel:+639617858547', 'cta' => 'Call now'],
        ['icon' => 'mail', 'label' => 'Email us', 'value' => 'hello@ziontek.co', 'href' => 'mailto:hello@ziontek.co', 'cta' => 'Send an email'],
        ['icon' => 'forum', 'label' => 'Message us on Facebook', 'value' => 'facebook.com/ziontekph', 'href' => 'https://facebook.com/ziontekph', 'cta' => 'Open Facebook'],
    ];
    $mapUrl = 'https://www.google.com/maps/search/?api=1&query='.urlencode('Cainta Greenland Executive Village Phase 8, Taytay, Rizal');
@endphp

<x-landing.page title="Contact Us" description="Contact ZionTek, the team behind ZionBuild AI. Call +63 961 785 8547, email hello@ziontek.co, or visit us in Taytay, Rizal. Office hours 9 AM to 5 PM.">
    <section class="relative overflow-hidden bg-surface py-16 lg:py-20">
        <div class="pointer-events-none absolute -top-24 left-1/2 h-[500px] w-[900px] -translate-x-1/2 rounded-full bg-primary/10 blur-3xl"></div>
        <div class="relative mx-auto max-w-3xl px-margin-sm text-center lg:px-margin">
            <span class="inline-flex items-center gap-2 rounded-full bg-surface-container-high px-4 py-1.5 font-label-md text-label-md text-primary shadow-xs">
                <span class="material-symbols-outlined text-[18px]">support_agent</span>
                Contact ZionTek
            </span>
            <h1 class="mt-6 font-display-hero text-display-hero-mobile font-extrabold tracking-tight text-on-surface sm:text-display-hero">
                We listen, we care, <span class="text-primary">and we deliver.</span>
            </h1>
            <p class="mx-auto mt-5 max-w-xl font-body-lg text-body-lg text-on-surface-variant">
                Questions about ZionBuild AI, pricing, or a custom system for your business? Reach us any way you like. A real person will get back to you.
            </p>
        </div>
    </section>

    <section class="bg-surface pb-16">
        <div class="mx-auto grid max-w-7xl gap-5 px-margin-sm md:grid-cols-3 lg:px-margin">
            @foreach ($channels as $channel)
                <a href="{{ $channel['href'] }}" @if (str_starts_with($channel['href'], 'http')) target="_blank" rel="noopener" @endif class="group rounded-2xl bg-surface-container-lowest p-7 shadow-xs ring-1 ring-on-surface/5 transition-all hover:-translate-y-0.5 hover:shadow-lg">
                    <span class="flex size-12 items-center justify-center rounded-2xl bg-primary-fixed text-primary">
                        <span class="material-symbols-outlined text-[26px]">{{ $channel['icon'] }}</span>
                    </span>
                    <p class="mt-5 font-label-md text-label-md text-on-surface-variant">{{ $channel['label'] }}</p>
                    <p class="mt-1 font-title-md text-title-md font-bold break-words text-on-surface">{{ $channel['value'] }}</p>
                    <span class="mt-4 inline-flex items-center gap-1 font-label-md text-label-md font-semibold text-primary">
                        {{ $channel['cta'] }}
                        <span class="material-symbols-outlined text-[18px] transition-transform group-hover:translate-x-0.5">arrow_forward</span>
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="bg-surface-container-low py-16">
        <div class="mx-auto grid max-w-7xl gap-6 px-margin-sm lg:grid-cols-5 lg:px-margin">
            <div class="space-y-5 rounded-2xl bg-surface-container-lowest p-7 shadow-xs lg:col-span-2">
                <div class="flex gap-4">
                    <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary-fixed text-primary">
                        <span class="material-symbols-outlined text-[24px]">location_on</span>
                    </span>
                    <div>
                        <p class="font-title-md text-title-md font-bold text-on-surface">Visit our office</p>
                        <p class="mt-1 font-body-md text-body-md text-on-surface-variant">Phase 8, Cainta Greenland Executive Village<br>Taytay, Rizal, Philippines</p>
                        <a href="{{ $mapUrl }}" target="_blank" rel="noopener" class="mt-2 inline-flex items-center gap-1 font-label-md text-label-md font-semibold text-primary">
                            Open in Google Maps
                            <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                        </a>
                    </div>
                </div>
                <div class="flex gap-4">
                    <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary-fixed text-primary">
                        <span class="material-symbols-outlined text-[24px]">schedule</span>
                    </span>
                    <div>
                        <p class="font-title-md text-title-md font-bold text-on-surface">Office hours</p>
                        <p class="mt-1 font-body-md text-body-md text-on-surface-variant">9:00 AM to 5:00 PM (Philippine time)</p>
                        <p class="mt-1 font-body-sm text-body-sm text-outline">Messages sent after hours are answered the next business day.</p>
                    </div>
                </div>
            </div>

            <div class="bg-brand-gradient relative overflow-hidden rounded-2xl p-8 text-on-primary shadow-xl lg:col-span-3 lg:p-10">
                <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px] opacity-10"></div>
                <div class="relative">
                    <p class="font-label-md text-label-md font-bold tracking-widest text-primary-fixed uppercase">Want to see it in action?</p>
                    <h2 class="mt-2 font-headline-lg text-headline-lg font-extrabold">Book a free ZionBuild AI demo.</h2>
                    <p class="mt-3 max-w-lg font-body-lg text-body-lg text-primary-fixed">
                        Leave your name and number or email, and tell us a little about your company. We'll walk you through ZionBuild using projects like yours.
                    </p>
                    <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ url('/') }}#demo" class="inline-flex items-center justify-center rounded-full bg-surface-container-lowest px-7 py-3.5 font-label-md text-label-md font-bold text-primary shadow-xl transition-all hover:bg-surface-container-low">
                            Book a Free Demo
                            <span class="material-symbols-outlined ml-2 text-[18px]">calendar_month</span>
                        </a>
                        @demo
                            <a href="{{ route('demo.how-it-works') }}" data-try-it class="inline-flex items-center justify-center rounded-full bg-white/15 px-7 py-3.5 font-label-md text-label-md font-bold text-on-primary ring-1 ring-white/30 transition-all hover:bg-white/25">
                                <span class="material-symbols-outlined mr-2 text-[18px]">play_circle</span>
                                Try the Live Demo
                            </a>
                        @enddemo
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-landing.page>
