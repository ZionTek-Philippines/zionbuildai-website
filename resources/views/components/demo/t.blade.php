@props(['en', 'tl'])

{{-- Bilingual copy: both versions render, and CSS shows the one matching <html data-demo-lang>. Pass plain strings as attributes or rich text as <x-slot:en> / <x-slot:tl>. --}}
<span data-lang="en">{{ $en }}</span><span data-lang="tl">{{ $tl }}</span>
