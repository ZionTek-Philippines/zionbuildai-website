{{-- A Field Reports card. $selectable adds the merge checkbox; $isTourCard marks the card the walkthrough points at. --}}
<article
    @if ($isTourCard) data-tour="report-card" @endif
    data-report-card
    data-this-week="{{ $report['thisWeek'] ? '1' : '0' }}"
    class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm transition hover:shadow-md has-checked:border-demo-blue has-checked:ring-2 has-checked:ring-demo-blue"
>
    <div class="relative aspect-[16/10] overflow-hidden bg-slate-200">
        <img src="{{ asset('images/demo/'.$report['photo'].'.jpg') }}" alt="{{ $report['note'] }}" loading="lazy" class="size-full object-cover transition duration-300 group-hover:scale-105">
        <span class="absolute right-2.5 bottom-2.5 inline-flex items-center gap-1 rounded-full bg-black/60 px-2.5 py-0.5 text-xs font-semibold text-white backdrop-blur-sm">
            <span class="material-symbols-outlined text-[14px]">photo_camera</span>
            {{ $report['photos'] }}
        </span>
        @if ($selectable)
            <label class="absolute top-3 left-3 flex size-7 cursor-pointer items-center justify-center rounded-md bg-white/90 shadow-sm">
                <input type="checkbox" data-report-select aria-label="Select for merge" class="size-4 cursor-pointer accent-demo-blue">
            </label>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-4">
        <h3 class="text-sm leading-snug font-semibold text-demo-navy">{{ $report['note'] }}</h3>

        <div class="mt-2.5 flex flex-wrap items-center gap-1.5">
            <span class="inline-flex items-center gap-1 rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-700">
                <span class="material-symbols-outlined text-[13px] text-indigo-600">person</span>
                {{ $report['by'] }}
            </span>
            @if ($report['type'] === 'Progress Update')
                <span class="rounded-md border border-indigo-100 bg-indigo-50 px-2 py-0.5 text-[11px] font-bold text-indigo-700">Progress Update</span>
            @else
                <span class="rounded-md border border-blue-100 bg-blue-50 px-2 py-0.5 text-[11px] font-semibold text-demo-blue">Daily Work Log</span>
            @endif
        </div>

        <div class="mt-2.5 mb-4 flex items-center justify-between text-xs">
            <span data-when-visible @if (! $report['visible']) hidden @endif class="inline-flex items-center gap-1 rounded-full border border-emerald-200/60 bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-demo-green">
                <span class="size-1.5 rounded-full bg-demo-green"></span>
                Client: Visible
            </span>
            <span data-when-hidden @if ($report['visible']) hidden @endif class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">
                <span class="size-1.5 rounded-full bg-slate-400"></span>
                Client: Hidden
            </span>
            <span class="text-[11px] font-medium text-slate-400">{{ $report['date'] }}</span>
        </div>

        <div class="mt-auto flex items-center justify-between gap-2 border-t border-slate-100 pt-3 text-xs">
            <button type="button" data-demo-toast-message="In the real app, View opens the full log with every photo and video." data-demo-toast-message-tl="Sa totoong app, bubuksan ng View ang buong log kasama lahat ng photos at video." class="inline-flex items-center gap-1 font-semibold text-demo-blue hover:underline">
                <span class="material-symbols-outlined text-[15px]">visibility</span>
                View
            </button>
            <button type="button" @if ($isTourCard) data-tour="ai-analyze" @endif data-demo-toast-message="In the real app, AI reviews these photos and suggests which task or milestone they match. You confirm before any progress changes." data-demo-toast-message-tl="Sa totoong app, rerebyuhin ng AI ang photos at sasabihin kung anong task o milestone ang tugma. Ikaw ang magko-confirm bago magbago ang progress." class="inline-flex items-center gap-1 font-semibold text-indigo-600 hover:underline">
                <span class="material-symbols-outlined text-[14px]">auto_awesome</span>
                Analyze with AI
            </button>
            <button type="button" @if ($isTourCard) data-tour="client-toggle" @endif data-client-toggle class="text-[11px] font-medium text-slate-500 transition hover:text-demo-green">
                <span data-when-visible @if (! $report['visible']) hidden @endif>Hide from client</span>
                <span data-when-hidden @if ($report['visible']) hidden @endif>Show to client</span>
            </button>
        </div>
    </div>
</article>
