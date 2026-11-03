@props(['time' => '9:41'])

{{-- A smartphone frame at real proportions (9 : 19.5), front view. The slot is the app screen. --}}
<div class="relative w-[260px] shrink-0">
    <span class="absolute top-24 -left-[3px] h-7 w-[3px] rounded-l bg-slate-700" aria-hidden="true"></span>
    <span class="absolute top-36 -left-[3px] h-12 w-[3px] rounded-l bg-slate-700" aria-hidden="true"></span>
    <span class="absolute top-32 -right-[3px] h-16 w-[3px] rounded-r bg-slate-700" aria-hidden="true"></span>

    <div class="aspect-[9/19.5] rounded-[44px] bg-slate-950 p-2.5 shadow-2xl ring-1 ring-slate-700">
        <div class="relative flex h-full flex-col overflow-hidden rounded-[34px] bg-white">
            <div class="flex items-center justify-between px-6 pt-3 pb-1 text-[11px] font-semibold text-slate-900" aria-hidden="true">
                <span>{{ $time }}</span>
                <span class="absolute top-2.5 left-1/2 h-[22px] w-[78px] -translate-x-1/2 rounded-full bg-black"></span>
                <span class="flex items-center gap-0.5">
                    <span class="material-symbols-outlined text-[13px]">signal_cellular_alt</span>
                    <span class="material-symbols-outlined text-[13px]">wifi</span>
                    <span class="material-symbols-outlined text-[13px]">battery_full</span>
                </span>
            </div>

            <div class="flex min-h-0 flex-1 flex-col">
                {{ $slot }}
            </div>

            <span class="mx-auto mt-1.5 mb-2 h-1 w-24 shrink-0 rounded-full bg-slate-900" aria-hidden="true"></span>
        </div>
    </div>
</div>
