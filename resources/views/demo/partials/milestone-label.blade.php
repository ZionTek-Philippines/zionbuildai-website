@if ($milestone['status'] === 'active')
    <span class="rounded-lg border border-blue-200/80 bg-blue-50 px-2 py-1 leading-tight whitespace-nowrap shadow-sm">
        <span class="block text-[10px] font-extrabold text-demo-blue uppercase">{{ $milestone['date'] }} · Now</span>
        <span class="block text-[9px] font-semibold text-slate-800">{{ $milestone['name'] }}</span>
    </span>
@else
    <span class="px-0.5 leading-tight">
        <span @class(['block text-[10px] font-extrabold uppercase', 'text-slate-800' => $milestone['status'] === 'done', 'text-slate-400' => $milestone['status'] === 'upcoming'])>{{ $milestone['date'] }}</span>
        <span class="line-clamp-2 block text-[9px] text-slate-500">M{{ $number }} — {{ $milestone['name'] }}</span>
    </span>
@endif
