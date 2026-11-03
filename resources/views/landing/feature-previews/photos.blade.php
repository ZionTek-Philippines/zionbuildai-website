<div class="flex flex-wrap items-center justify-between gap-3 rounded-xl bg-surface-container-low p-4">
    <div class="flex items-center gap-3">
        <span class="material-symbols-outlined text-[24px] text-primary">camera_alt</span>
        <div>
            <p class="font-title-md text-title-md font-bold text-on-surface">12 New Site Photos Captured</p>
            <p class="font-body-sm text-body-sm text-on-surface-variant">Timestamp: Today 02:15 PM • Tagged: Rebar Inspection</p>
        </div>
    </div>
    <span class="rounded-full bg-tertiary-container/15 px-3 py-1 font-label-sm text-label-sm font-bold text-tertiary">GPS Verified</span>
</div>

<div class="grid grid-cols-2 gap-3">
    @foreach (['Column 2F Rebars', 'Formwork Alignment'] as $caption)
        <div class="relative h-44 overflow-hidden rounded-xl bg-surface-container">
            <img src="{{ asset('images/site-inspection.jpg') }}" alt="Progress photo: {{ $caption }}" class="size-full object-cover" loading="lazy">
            <span class="absolute bottom-2 left-2 rounded bg-on-surface/75 px-2 py-0.5 font-label-sm text-label-sm text-on-primary">{{ $caption }}</span>
        </div>
    @endforeach
</div>
