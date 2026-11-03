<div class="mx-auto aspect-9/16 max-h-[640px] overflow-hidden rounded-xl bg-on-surface">
    <video
        src="{{ asset('storage/'.rawurlencode($feature['video'])) }}"
        class="size-full object-contain"
        controls
        playsinline
        loop
        preload="metadata"
        aria-label="Walkthrough: {{ $feature['title'] }}"
    ></video>
</div>
