@props(['dark' => false])

<button
    type="button"
    data-tour-info
    aria-label="What is this?"
    {{ $attributes->class([
        'flex size-6 shrink-0 items-center justify-center rounded-full text-xs font-bold transition',
        'bg-white/10 text-white/70 hover:bg-white/20 hover:text-white' => $dark,
        'bg-white/80 text-slate-500 shadow-sm hover:bg-white hover:text-slate-800' => ! $dark,
    ]) }}
>i</button>
