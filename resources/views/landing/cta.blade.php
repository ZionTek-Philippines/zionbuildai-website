@php
    $expectations = [
        ['icon' => 'support_agent', 'title' => 'A personal walkthrough', 'body' => 'We show ZionBuild using projects like yours, not a generic slideshow.'],
        ['icon' => 'fact_check', 'title' => 'A quick project assessment', 'body' => 'We look at your sites, workforce and current reporting workflow.'],
        ['icon' => 'schedule', 'title' => 'We reach out first', 'body' => 'Leave your details and we\'ll contact you to set a convenient time.'],
    ];
    $inputClass = 'w-full rounded-xl border border-outline-variant bg-surface-container-lowest px-4 py-3 font-body-md text-body-md text-on-surface placeholder:text-outline outline-none transition focus:border-primary focus:ring-4 focus:ring-primary/15';
    $errorInputClass = 'border-error focus:border-error focus:ring-error/15';
    $contactError = $errors->first('contact_number') ?: $errors->first('email');
@endphp

<section id="demo" data-track-section="Book a Demo" class="w-full scroll-mt-20 bg-surface py-20">
    <div class="mx-auto max-w-7xl px-margin-sm lg:px-margin">
        <div class="bg-brand-gradient relative overflow-hidden rounded-3xl p-6 text-on-primary shadow-2xl sm:p-10 lg:p-16">
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px] opacity-10"></div>

            <div class="relative z-10 grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
                <div class="space-y-6 text-center lg:text-left">
                    <span class="inline-flex items-center gap-2 rounded-full bg-surface-container-lowest/20 px-3.5 py-1 font-label-md text-label-md font-semibold text-on-primary backdrop-blur-md">
                        Institutional Control • Zero Guesswork
                    </span>
                    <h2 class="font-headline-lg text-headline-lg font-extrabold text-on-primary">
                        Ready to take control of your construction projects?
                    </h2>
                    <p class="mx-auto max-w-xl font-body-lg text-body-lg text-primary-fixed lg:mx-0">
                        See how ZionBuild AI helps you manage construction with greater visibility and accountability. Book a free demo in under a minute.
                    </p>

                    <ul class="mx-auto max-w-md space-y-4 pt-2 text-left lg:mx-0">
                        @foreach ($expectations as $expectation)
                            <li class="flex gap-4">
                                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-surface-container-lowest/15 ring-1 ring-white/20">
                                    <span class="material-symbols-outlined text-[22px] text-on-primary">{{ $expectation['icon'] }}</span>
                                </span>
                                <span>
                                    <span class="block font-label-md text-label-md font-bold text-on-primary">{{ $expectation['title'] }}</span>
                                    <span class="block font-body-md text-body-md text-primary-fixed">{{ $expectation['body'] }}</span>
                                </span>
                            </li>
                        @endforeach
                    </ul>

                    <p class="font-label-sm text-label-sm font-medium text-primary-fixed">
                        No credit card required • Tailored setup for your active job sites
                    </p>
                </div>

                <div class="rounded-3xl bg-surface-container-lowest p-6 text-on-surface shadow-2xl ring-1 ring-on-surface/5 sm:p-8">
                    @if (session('demo_booked'))
                        <div class="flex flex-col items-center py-8 text-center" role="status">
                            <span class="flex size-16 items-center justify-center rounded-full bg-tertiary-fixed/40 text-tertiary">
                                <span class="material-symbols-outlined text-[36px]">check_circle</span>
                            </span>
                            <h3 class="mt-5 font-headline-md text-headline-md font-extrabold text-on-surface">
                                Thank you, {{ session('demo_booked') }}!
                            </h3>
                            <p class="mt-2 max-w-sm font-body-md text-body-md text-on-surface-variant">
                                Your demo request is in. Our team will reach out shortly to set a time that works for you.
                            </p>
                            @demo
                                <a href="{{ route('demo.how-it-works') }}" data-try-it class="bg-success-gradient mt-6 inline-flex items-center justify-center rounded-full px-7 py-3 font-label-md text-label-md text-white shadow-md shadow-emerald-500/30 transition-all hover:opacity-95">
                                    <span class="material-symbols-outlined mr-2 text-[18px]">play_circle</span>
                                    Explore the live demo while you wait
                                </a>
                            @enddemo
                        </div>
                    @else
                        <div class="flex items-center gap-3">
                            <span class="flex size-11 items-center justify-center rounded-2xl bg-primary-fixed text-primary">
                                <span class="material-symbols-outlined text-[24px]">calendar_month</span>
                            </span>
                            <div>
                                <h3 class="font-headline-md text-xl font-extrabold text-on-surface">Book a Free Demo</h3>
                                <p class="font-label-sm text-label-sm text-on-surface-variant">Takes less than a minute</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('demo-booking.store') }}" class="mt-6 space-y-5" data-track-form="Book a Demo" novalidate>
                            @csrf
                            {{-- Honeypot: hidden from people, filled in by bots --}}
                            <div class="hidden" aria-hidden="true">
                                <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                            </div>
                            <input type="hidden" name="analytics_visitor_id">
                            <input type="hidden" name="analytics_visit_id">

                            <div>
                                <label for="booking-name" class="mb-1.5 block font-label-md text-label-md font-semibold text-on-surface">
                                    Your name <span class="text-error">*</span>
                                </label>
                                <input id="booking-name" type="text" name="name" data-track-field="Name" value="{{ old('name') }}" required maxlength="100" autocomplete="name" placeholder="Juan dela Cruz" @class([$inputClass, $errorInputClass => $errors->has('name')])>
                                @error('name') <p class="mt-1.5 font-label-sm text-label-sm text-error">{{ $message }}</p> @enderror
                            </div>

                            <fieldset>
                                <legend class="mb-1.5 font-label-md text-label-md font-semibold text-on-surface">
                                    How can we reach you? <span class="text-error">*</span>
                                </legend>
                                <p class="mb-2.5 font-label-sm text-label-sm text-on-surface-variant">Fill in at least one, whichever you prefer.</p>
                                <div class="grid gap-3 sm:grid-cols-[1fr_auto_1fr] sm:items-center">
                                    <div class="relative">
                                        <span class="material-symbols-outlined pointer-events-none absolute top-1/2 left-3.5 -translate-y-1/2 text-[20px] text-outline">call</span>
                                        <input type="tel" name="contact_number" data-track-field="Contact number" value="{{ old('contact_number') }}" autocomplete="tel" placeholder="0917 123 4567" aria-label="Contact number" @class([$inputClass, 'pl-11', $errorInputClass => $contactError])>
                                    </div>
                                    <span class="text-center font-label-sm text-label-sm font-semibold tracking-wider text-outline uppercase">or</span>
                                    <div class="relative">
                                        <span class="material-symbols-outlined pointer-events-none absolute top-1/2 left-3.5 -translate-y-1/2 text-[20px] text-outline">mail</span>
                                        <input type="email" name="email" data-track-field="Email" value="{{ old('email') }}" maxlength="150" autocomplete="email" placeholder="you@company.com" aria-label="Email address" @class([$inputClass, 'pl-11', $errorInputClass => $contactError])>
                                    </div>
                                </div>
                                @if ($contactError)
                                    <p class="mt-1.5 font-label-sm text-label-sm text-error">{{ $contactError }}</p>
                                @endif
                            </fieldset>

                            <div>
                                <label for="booking-company" class="mb-1.5 block font-label-md text-label-md font-semibold text-on-surface">
                                    Tell us about your company <span class="text-error">*</span>
                                </label>
                                <textarea id="booking-company" name="company_info" data-track-field="Company info" rows="3" required maxlength="1000" placeholder="e.g. Santos Builders, general contractor with 3 ongoing residential projects in Cavite" @class([$inputClass, 'resize-none', $errorInputClass => $errors->has('company_info')])>{{ old('company_info') }}</textarea>
                                @error('company_info')
                                    <p class="mt-1.5 font-label-sm text-label-sm text-error">{{ $message }}</p>
                                @else
                                    <p class="mt-1.5 font-label-sm text-label-sm text-on-surface-variant">Your company name, what you build, or a short description is enough.</p>
                                @enderror
                            </div>

                            <button type="submit" class="bg-brand-gradient flex w-full items-center justify-center gap-2 rounded-full px-8 py-4 font-label-md text-base font-bold text-on-primary shadow-lg shadow-primary/30 transition-all hover:opacity-95 focus:ring-4 focus:ring-primary/25 focus:outline-none">
                                Book a Demo
                                <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                            </button>

                            <div class="flex gap-3 rounded-2xl bg-surface-container-low px-4 py-3.5">
                                <span class="material-symbols-outlined shrink-0 text-[22px] text-tertiary">verified_user</span>
                                <p class="font-label-sm text-label-sm leading-relaxed text-on-surface-variant">
                                    <span class="block font-label-md text-label-md font-bold text-on-surface">Your privacy is our #1 priority.</span>
                                    Your details stay confidential. We use them only to arrange your demo and never share or sell them to anyone.
                                </p>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
