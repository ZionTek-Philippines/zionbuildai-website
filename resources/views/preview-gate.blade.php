{{-- Temporary password prompt shown in place of the homepage until the marketing launch (see RequireSitePreviewPassword) --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">

        <title>{{ config('app.name', 'ZionBuild AI') }} — Coming soon</title>

        @fonts

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block">

        @vite(['resources/css/app.css'])
    </head>
    <body class="bg-surface font-body-md text-body-md text-on-surface antialiased">
        <main class="relative flex min-h-dvh items-center justify-center overflow-hidden px-margin-sm py-16">
            <div class="pointer-events-none absolute inset-0" aria-hidden="true">
                <div class="absolute -top-40 left-1/2 h-[560px] w-[1000px] -translate-x-1/2 rounded-full bg-indigo-500/30 blur-[120px]"></div>
                <div class="absolute top-1/3 -left-32 size-[480px] animate-glow-drift rounded-full bg-violet-500/30 blur-[110px] motion-reduce:animate-none"></div>
                <div class="absolute -right-32 bottom-0 size-[460px] animate-glow-drift rounded-full bg-emerald-400/25 blur-[110px] [animation-delay:-7s] motion-reduce:animate-none"></div>
            </div>

            <section class="relative w-full max-w-sm rounded-3xl bg-surface-container-lowest p-8 text-center shadow-2xl ring-1 ring-on-surface/5">
                <div class="flex justify-center">
                    @include('landing.logo')
                </div>

                <span class="mx-auto mt-8 flex size-12 items-center justify-center rounded-2xl bg-primary-fixed text-primary">
                    <span class="material-symbols-outlined text-[26px]">lock</span>
                </span>
                <h1 class="mt-4 font-headline-sm text-headline-sm font-extrabold text-on-surface">Coming soon</h1>
                <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">This page is private for now. Enter the password to continue.</p>

                <form method="POST" action="{{ route('site-preview.store') }}" class="mt-6 space-y-3 text-left">
                    @csrf
                    <label class="block">
                        <span class="sr-only">Password</span>
                        <input type="password" name="password" required autofocus autocomplete="current-password" placeholder="Password" class="w-full rounded-xl border border-outline-variant bg-surface-container-lowest px-4 py-3 text-on-surface outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                    </label>
                    @error('password')
                        <p class="font-body-sm text-body-sm text-error">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="bg-brand-gradient flex w-full items-center justify-center gap-1.5 rounded-full py-3 font-label-md text-label-md text-on-primary shadow-md transition-opacity hover:opacity-95">
                        Continue
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </button>
                </form>
            </section>
        </main>
    </body>
</html>
