@props([
    'title',
    'description',
    'icon',
    'intro',
    'updated' => 'November 2, 2026',
    'sections' => [],
])

{{--
    Long-form policy page with a sticky table of contents.
    Each section: ['id' => ..., 'title' => ..., 'blocks' => [paragraph string | ['list' => [...]]]].
    Copy is authored by us (not user input), so paragraphs may contain simple inline HTML like links and <strong>.
--}}
<x-landing.page :title="$title" :description="$description">
    <section class="relative overflow-hidden bg-surface-container-low py-16 lg:py-20">
        <div class="pointer-events-none absolute -top-24 left-1/2 h-[400px] w-[800px] -translate-x-1/2 rounded-full bg-primary/10 blur-3xl"></div>
        <div class="relative mx-auto max-w-4xl px-margin-sm text-center lg:px-margin">
            <span class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-surface-container-lowest text-primary shadow-xs">
                <span class="material-symbols-outlined text-[30px]">{{ $icon }}</span>
            </span>
            <h1 class="mt-5 font-headline-lg text-headline-lg font-extrabold text-on-surface">{{ $title }}</h1>
            <p class="mx-auto mt-3 max-w-2xl font-body-lg text-body-lg text-on-surface-variant">{{ $intro }}</p>
            <p class="mt-4 font-label-sm text-label-sm text-outline">Last updated: {{ $updated }}</p>
        </div>
    </section>

    <div class="mx-auto grid max-w-6xl gap-10 px-margin-sm py-14 lg:grid-cols-[16rem_1fr] lg:gap-14 lg:px-margin">
        <nav aria-label="On this page" class="hidden lg:block">
            <div class="sticky top-28 space-y-1">
                <p class="mb-3 font-label-md text-label-md tracking-wider text-on-surface uppercase">On this page</p>
                @foreach ($sections as $section)
                    <a href="#{{ $section['id'] }}" class="block rounded-lg px-3 py-1.5 font-body-sm text-body-sm text-on-surface-variant transition-colors hover:bg-surface-container-low hover:text-on-surface">
                        {{ $loop->iteration }}. {{ $section['title'] }}
                    </a>
                @endforeach
            </div>
        </nav>

        <article class="min-w-0 space-y-10">
            @foreach ($sections as $section)
                <section id="{{ $section['id'] }}" class="scroll-mt-28">
                    <h2 class="font-headline-md text-xl font-extrabold text-on-surface">{{ $loop->iteration }}. {{ $section['title'] }}</h2>
                    <div class="mt-3 space-y-3 font-body-md text-body-md leading-relaxed text-on-surface-variant [&_a]:font-semibold [&_a]:text-primary [&_a]:underline-offset-2 hover:[&_a]:underline [&_strong]:text-on-surface">
                        @foreach ($section['blocks'] as $block)
                            @if (is_array($block))
                                <ul class="space-y-2 pl-1">
                                    @foreach ($block['list'] as $item)
                                        <li class="flex gap-2.5">
                                            <span class="material-symbols-outlined mt-0.5 shrink-0 text-[18px] text-primary">check_circle</span>
                                            <span>{!! $item !!}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>{!! $block !!}</p>
                            @endif
                        @endforeach
                    </div>
                </section>
            @endforeach

            <div class="rounded-2xl bg-surface-container-low p-6">
                <p class="font-title-md text-title-md font-bold text-on-surface">Questions about this page?</p>
                <p class="mt-1 font-body-md text-body-md text-on-surface-variant">
                    Email <a href="mailto:hello@ziontek.co" class="font-semibold text-primary">hello@ziontek.co</a>, call <a href="tel:+639617858547" class="font-semibold text-primary">+63 961 785 8547</a>, or visit our <a href="{{ route('contact') }}" class="font-semibold text-primary">Contact page</a>.
                </p>
            </div>
        </article>
    </div>
</x-landing.page>
