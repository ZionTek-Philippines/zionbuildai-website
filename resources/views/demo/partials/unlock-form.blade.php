{{-- Unlock form shown under the top of a gated module (its title, tabs and KPI tiles) until the visitor unlocks the demo.
     The rest of the module is not rendered, so it is not in the page source either. --}}
@php
    use App\Models\DemoLead;

    $gatedModules = [
        'demo.finances' => ['en' => 'See the rest: budget vs actual per category, every expense, and your bank and cash records.', 'tl' => 'Makita ang iba pa: budget vs actual bawat category, bawat gastos, at ang records ng bank at cash mo.'],
        'demo.payroll' => ['en' => 'See the rest: each worker\'s pay, vale and cash advances, and past payroll runs.', 'tl' => 'Makita ang iba pa: sahod ng bawat worker, vale at cash advance, at mga nakaraang payroll.'],
        'demo.client-billing' => ['en' => 'See the rest: every client payment, and the portal your homeowner sees.', 'tl' => 'Makita ang iba pa: bawat bayad ng client, at ang portal na nakikita ng homeowner mo.'],
    ];
    $routeName = request()->route()->getName();
    $module = $gatedModules[$routeName];
    $inputClass = 'w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-demo-blue focus:ring-2 focus:ring-demo-blue/20';
@endphp

<div class="relative min-h-[720px] overflow-hidden rounded-3xl">
    <div class="pointer-events-none space-y-6 opacity-60 blur-[3px] select-none" aria-hidden="true">
        <div class="h-72 rounded-2xl bg-white shadow-sm"></div>
        <div class="h-48 rounded-2xl bg-white shadow-sm"></div>
        <div class="h-48 rounded-2xl bg-white shadow-sm"></div>
    </div>
    {{-- Dark frosted glass behind the form, so the locked area reads as locked and the card stands out --}}
    <div class="pointer-events-none absolute inset-0 bg-linear-to-b from-slate-950/55 to-slate-950/70 ring-1 ring-white/10 backdrop-blur-xl backdrop-saturate-150 ring-inset" aria-hidden="true"></div>

    <div class="absolute inset-x-0 top-0 flex justify-center px-3 pt-6 sm:pt-10">
        <section class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl shadow-black/40 ring-1 ring-white/20 sm:p-8">
            <div class="flex items-start justify-between gap-3">
                <span class="flex size-12 items-center justify-center rounded-2xl bg-blue-50 text-demo-blue">
                    <span class="material-symbols-outlined text-[26px]">lock_open</span>
                </span>
                <x-demo.lang-toggle />
            </div>

            <h2 class="mt-4 font-demo text-xl font-extrabold tracking-tight text-demo-navy">
                <x-demo.t en="Unlock the full demo" tl="I-unlock ang buong demo" />
            </h2>
            <p class="mt-1 text-sm text-slate-500"><x-demo.t :en="$module['en']" :tl="$module['tl']" /></p>
            <p class="mt-3 rounded-xl bg-slate-50 px-3.5 py-2.5 text-xs text-slate-600">
                <x-demo.t
                    en="Tell us a little about you to open Finances, Payroll and Client Billing. It takes 10 seconds, and you only do it once."
                    tl="Konting detalye lang para mabuksan ang Finances, Payroll at Client Billing. 10 segundo lang, at isang beses lang." />
            </p>

            <form method="POST" action="{{ route('demo.unlock') }}" class="mt-5 space-y-3.5">
                @csrf
                <input type="hidden" name="unlocked_from" value="{{ $routeName }}">
                {{-- Honeypot: hidden from people, filled in by bots --}}
                <div class="hidden" aria-hidden="true">
                    <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                </div>

                <label class="block">
                    <span class="mb-1 block text-xs font-semibold text-slate-700"><x-demo.t en="Your name" tl="Pangalan mo" /></span>
                    <input type="text" name="name" value="{{ old('name') }}" required maxlength="100" autocomplete="name" class="{{ $inputClass }}">
                    @error('name') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="mb-1 block text-xs font-semibold text-slate-700"><x-demo.t en="Mobile number" tl="Mobile number" /></span>
                    <input type="tel" name="mobile" value="{{ old('mobile') }}" required placeholder="0917 123 4567" autocomplete="tel" class="{{ $inputClass }}">
                    @error('mobile') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="mb-1 block text-xs font-semibold text-slate-700"><x-demo.t en="Company or project" tl="Kumpanya o project" /></span>
                    <input type="text" name="company" value="{{ old('company') }}" required maxlength="150" autocomplete="organization" class="{{ $inputClass }}">
                    @error('company') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="mb-1 block text-xs font-semibold text-slate-700"><x-demo.t en="I am a…" tl="Ako ay…" /></span>
                    <select name="role" required class="{{ $inputClass }}">
                        <option value="" disabled @selected(! old('role'))>—</option>
                        @foreach (DemoLead::ROLES as $value => $label)
                            <option value="{{ $value }}" @selected(old('role') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('role') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>

                <button type="submit" class="flex w-full items-center justify-center gap-1.5 rounded-full bg-demo-blue py-3 font-demo text-sm font-bold text-white shadow-lg shadow-demo-blue/30 transition hover:bg-blue-700">
                    <x-demo.t en="Unlock the demo" tl="I-unlock ang demo" />
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </button>
            </form>

            <p class="mt-4 text-center text-[11px] text-slate-400">
                <x-demo.t en="We only use this to follow up about ZionBuild." tl="Gagamitin lang namin ito para sa follow-up tungkol sa ZionBuild." />
            </p>
        </section>
    </div>
</div>
