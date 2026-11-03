{{-- The worker's "Time-out + Photo of Accomplishment" form (mirrors ActivityForm in the real app). A bottom sheet on phones, a dialog on larger screens. --}}
<div data-report-modal role="dialog" aria-modal="true" aria-labelledby="report-form-title" class="fixed inset-0 z-[70] hidden items-end justify-center bg-[#0a1120]/70 backdrop-blur-[3px] sm:items-center sm:p-6">
    <form data-report-form class="flex max-h-[88dvh] w-full flex-col rounded-t-3xl bg-white shadow-2xl sm:max-w-md sm:rounded-3xl">
        <div class="mx-auto mt-2.5 h-1 w-9 shrink-0 rounded-full bg-slate-300 sm:hidden"></div>
        <div class="flex shrink-0 items-start justify-between gap-3 border-b border-slate-100 px-5 py-3">
            <div>
                <h2 id="report-form-title" class="font-demo text-base font-extrabold tracking-tight text-slate-900">Time-out + Photo of Accomplishment</h2>
                <p class="text-[11px] font-medium text-slate-500"><x-demo.t en="What a worker fills in before going home" tl="Ito ang fina-fill up ng worker bago umuwi" /></p>
            </div>
            <button type="button" data-report-close aria-label="Close" class="flex size-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:text-slate-800">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>

        <div class="flex-1 space-y-4 overflow-y-auto px-5 py-4 text-sm">
            <div class="space-y-1">
                <span class="block text-[11px] font-bold tracking-wider text-slate-500 uppercase">Employee</span>
                <div class="flex items-center justify-between rounded-xl border border-slate-200 p-2.5">
                    <div class="flex items-center gap-2.5">
                        <span class="flex size-7 items-center justify-center rounded-full bg-rose-600 text-xs font-bold text-white">DL</span>
                        <div class="leading-tight">
                            <span class="block font-semibold text-slate-900">Danilo Lim</span>
                            <span class="block text-[11px] text-slate-400">Steelman</span>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-[18px] text-slate-400">expand_more</span>
                </div>
            </div>

            <div class="space-y-1.5">
                <span class="block text-[11px] font-bold tracking-wider text-slate-500 uppercase">Photos / Video ng Work Accomplishment</span>
                <div class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-blue-300 bg-blue-50/50 p-3.5 text-center">
                    <span class="mb-1 flex size-8 items-center justify-center rounded-full bg-blue-100 text-demo-blue">
                        <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                    </span>
                    <p class="text-xs font-semibold text-blue-700">Tap para kumuha ng photo o video</p>
                    <p class="text-[11px] text-slate-400">Camera o Gallery</p>
                </div>
                <div class="flex gap-2.5 pt-1">
                    @foreach (['fr-10', 'fr-02', 'fr-01'] as $thumbnail)
                        <img src="{{ asset('images/demo/'.$thumbnail.'.jpg') }}" alt="" class="size-16 rounded-lg border border-slate-200 object-cover">
                    @endforeach
                    <span class="flex size-16 flex-col items-center justify-center rounded-lg border border-dashed border-slate-300 bg-slate-50 text-[11px] font-medium text-slate-500">
                        <span class="text-base leading-none font-bold text-slate-400">+</span>
                        Add
                    </span>
                </div>
                <p class="text-[11px] text-slate-500 italic">Pwede mag-upload ng maraming photo at video.</p>
            </div>

            <label class="block space-y-1">
                <span class="block text-[11px] font-bold tracking-wider text-slate-500 uppercase">Ano ginawa mo today?</span>
                <textarea rows="2" class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50/60 p-2.5 font-medium text-slate-900 outline-none focus:border-demo-blue focus:bg-white">Nag-tali ng rebar sa grid B–C, 2nd floor slab.</textarea>
            </label>

            <div class="space-y-1">
                <span class="block text-[11px] font-bold tracking-wider text-slate-500 uppercase">Task (optional)</span>
                <div class="flex items-center justify-between rounded-xl border border-slate-200 p-2.5 font-medium text-slate-800">
                    <span class="flex items-center gap-2 truncate">
                        <span class="size-2 shrink-0 rounded-full bg-demo-blue"></span>
                        Second-floor slab rebar inspection
                    </span>
                    <span class="material-symbols-outlined text-[18px] text-slate-400">expand_more</span>
                </div>
            </div>

            <div class="space-y-1">
                <span class="block text-[11px] font-bold tracking-wider text-slate-500 uppercase">Work Date</span>
                <div class="flex items-center justify-between rounded-xl border border-slate-200 p-2.5 font-medium text-slate-800">
                    Oct 31, 2026
                    <span class="material-symbols-outlined text-[18px] text-slate-400">calendar_today</span>
                </div>
            </div>
        </div>

        <div class="flex shrink-0 flex-col gap-1.5 border-t border-slate-100 px-5 py-3">
            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-red-600 py-3.5 font-demo text-sm font-bold text-white shadow-lg shadow-red-600/25 transition hover:bg-red-700">
                Submit
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </button>
            <button type="button" data-report-close class="py-1.5 text-xs font-medium text-slate-500 hover:text-slate-800">Cancel</button>
        </div>
    </form>
</div>
