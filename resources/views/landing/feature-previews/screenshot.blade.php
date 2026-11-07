{{-- A real screenshot of the demo (public/images); clicking it opens the live demo unless DEMO_DISABLED --}}
<a @demo href="{{ route($feature['demoRoute']) }}" data-try-it aria-label="Try the live demo" @enddemo class="group relative block overflow-hidden rounded-xl bg-surface-container-low ring-1 ring-on-surface/5">
    <img src="{{ asset('images/'.$feature['image']) }}" alt="{{ $feature['imageAlt'] }}" class="w-full transition-transform duration-500 group-hover:scale-[1.02]" loading="lazy">

    @demo
        <span class="pointer-events-none absolute inset-x-0 bottom-0 h-2/5 bg-linear-to-t from-surface-container-lowest via-surface-container-lowest/60 to-transparent"></span>

        <span class="absolute inset-x-0 bottom-4 flex justify-center">
            <span class="bg-success-gradient inline-flex items-center rounded-full px-6 py-3 font-label-md text-label-md font-semibold text-white shadow-lg shadow-emerald-600/40 ring-4 ring-white/70 transition-transform group-hover:scale-105">
                <span class="material-symbols-outlined mr-2 text-[18px]">play_circle</span>
                Try it! Live Demo
            </span>
        </span>
    @enddemo
</a>
