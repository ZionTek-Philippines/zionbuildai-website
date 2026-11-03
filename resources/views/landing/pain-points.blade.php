@php
    $painPoints = [
        ['icon' => 'account_balance_wallet', 'question' => 'Nasaan na ang budget?', 'description' => 'Unlogged hardware runs, unreceipted emergency purchases, and zero real-time cash flow monitoring until the drawdown runs dry.'],
        ['icon' => 'pie_chart', 'question' => 'Ilang percent na ang project?', 'description' => 'Estimates based on gut feel rather than computed S-Curve milestones. Contractors and clients end up in disputes during billing releases.'],
        ['icon' => 'badge', 'question' => 'Sino ang pumasok today?', 'description' => 'Foreman logbooks smeared with cement dust, disputed overtime hours, and ghost workers inflating Friday afternoon sahod payouts.'],
        ['icon' => 'inventory_2', 'question' => 'May materials pa ba?', 'description' => 'Masons waiting idle because Portland cement or 12mm deformed bars ran out unexpectedly without purchase orders dispatched.'],
        ['icon' => 'pending_actions', 'question' => 'On schedule pa ba tayo?', 'description' => 'Weather disruptions during monsoon season without schedule adjustments, pushing critical turnover targets weeks behind contract terms.'],
        ['icon' => 'chat', 'question' => 'Nasaan ang latest client report?', 'description' => 'Scrambling through hundreds of unorganized Viber and Messenger group photos every weekend just to craft a single billing accomplishment update.'],
    ];
@endphp

<section data-track-section="Pain Points" class="w-full bg-surface-container-low py-20">
    <div class="mx-auto max-w-7xl px-margin-sm lg:px-margin">
        <div class="mx-auto mb-14 max-w-3xl space-y-space-xs text-center">
            <span class="font-label-md text-label-md font-bold tracking-wider text-primary uppercase">The Real Site Realities</span>
            <h2 class="font-headline-lg text-headline-lg font-extrabold text-on-surface">
                Construction is hard enough. Managing it shouldn't be.
            </h2>
            <p class="font-body-lg text-body-lg text-on-surface-variant">
                Philippine job sites move fast. Don't lose money on lost receipts, miscalculated vale, and missing reports.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($painPoints as $painPoint)
                <div class="space-y-3 rounded-2xl bg-surface-container-lowest p-6 shadow-xs transition-shadow hover:shadow-md">
                    <div class="flex size-12 items-center justify-center rounded-xl bg-surface-container text-primary">
                        <span class="material-symbols-outlined text-[26px]">{{ $painPoint['icon'] }}</span>
                    </div>
                    <h3 class="font-title-md text-title-md font-bold text-on-surface">"{{ $painPoint['question'] }}"</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant">{{ $painPoint['description'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="mx-auto mt-12 max-w-2xl rounded-xl bg-surface-container-high/60 p-4 text-center">
            <p class="font-title-md text-title-md font-semibold text-on-surface">
                Scattered Excel files, GC chats and paper receipts — <span class="text-primary">ZionBuild puts it all in one system.</span>
            </p>
        </div>
    </div>
</section>
