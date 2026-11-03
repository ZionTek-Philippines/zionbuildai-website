@php
    $footerColumns = [
        'Product' => ['Features' => url('/').'#features', 'How It Works' => url('/').'#how-it-works', 'FAQ' => url('/').'#faq', 'Book a Free Demo' => url('/').'#demo'],
        'Company' => ['About' => route('about'), 'Contact' => route('contact')],
        'Legal' => ['Privacy Policy' => route('privacy-policy'), 'Terms of Service' => route('terms-of-service'), 'Data Security' => route('data-security')],
    ];

    $socialLinks = [
        'ZionTek on Facebook' => ['icon' => 'forum', 'href' => 'https://facebook.com/ziontekph'],
        'Email hello@ziontek.co' => ['icon' => 'mail', 'href' => 'mailto:hello@ziontek.co'],
        'Call +63 961 785 8547' => ['icon' => 'call', 'href' => 'tel:+639617858547'],
    ];
@endphp

<footer data-track-section="Footer" class="w-full bg-surface-container-lowest pt-space-xl pb-space-lg shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
    <div class="mx-auto max-w-7xl px-margin-sm lg:px-margin">
        <div class="grid grid-cols-1 gap-space-xl pb-space-xl md:grid-cols-2 lg:grid-cols-5">
            <div class="space-y-space-md lg:col-span-2">
                @include('landing.logo')
                <p class="font-label-md text-label-md font-bold tracking-widest text-primary">REAL CONSTRUCTION. REAL TALK.</p>
                <p class="max-w-sm font-body-sm text-body-sm text-on-surface-variant">Institutional precision, milestone governance, and transparent cost assurance engineered for Philippine commercial and residential builders.</p>
                <div class="flex items-center gap-space-md pt-space-xs">
                    @foreach ($socialLinks as $label => $link)
                        <a href="{{ $link['href'] }}" aria-label="{{ $label }}" title="{{ $label }}" @if (str_starts_with($link['href'], 'http')) target="_blank" rel="noopener" @endif class="flex size-9 items-center justify-center rounded-full bg-surface-container text-on-surface-variant transition-colors hover:bg-surface-container-high hover:text-on-surface">
                            <span class="material-symbols-outlined text-[18px]">{{ $link['icon'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            @foreach ($footerColumns as $heading => $links)
                <div class="space-y-space-sm">
                    <p class="font-label-md text-label-md tracking-wider text-on-surface uppercase">{{ $heading }}</p>
                    <ul class="space-y-space-xs font-body-sm text-body-sm text-on-surface-variant">
                        @foreach ($links as $label => $href)
                            <li><a href="{{ $href }}" class="transition-colors hover:text-on-surface">{{ $label }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="flex flex-col items-center justify-between gap-space-md pt-space-lg font-body-sm text-body-sm text-on-surface-variant sm:flex-row">
            <p>&copy; {{ date('Y') }} ZionTek. All rights reserved.</p>
            <p class="font-label-sm text-label-sm text-outline">Built for the Philippine Construction Industry</p>
        </div>
    </div>
</footer>
