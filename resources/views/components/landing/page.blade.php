@props([
    'title' => null,
    'description' => 'One system for contractors, construction teams and homeowners. Daily progress photos, attendance, payroll, budgets, materials and schedules in one platform.',
    'robots' => null,
])

{{-- Shared shell for every public marketing page: head, header, footer and the "Try it!" demo popup --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ? $title.' | '.config('app.name', 'ZionBuild AI') : config('app.name', 'ZionBuild AI').' — Construction Management for Philippine Builders' }}</title>
        <meta name="description" content="{{ $description }}">
        @if ($robots)
            <meta name="robots" content="{{ $robots }}">
        @endif
        @guest
            <meta name="site-analytics" content="{{ route('site-events.store') }}">
        @endguest

        @fonts

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-surface font-body-md text-body-md text-on-surface antialiased selection:bg-primary-fixed selection:text-on-primary-fixed">
        @include('landing.header')

        <main class="w-full bg-surface pt-20">
            {{ $slot }}
        </main>

        @include('landing.footer')
        @demo
            @include('landing.try-it-modal')
        @enddemo
    </body>
</html>
