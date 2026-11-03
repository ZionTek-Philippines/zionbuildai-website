@php
    $faqs = [
        ['question' => 'What is ZionBuild AI?', 'answer' => 'ZionBuild AI is a unified construction management system engineered specifically for Philippine contractors, project engineers, and homeowners. It synchronizes daily progress photos, worker attendance, payroll with vale deductions, budget vs. actual expenses, and milestone S-Curves in one seamless platform.'],
        ['question' => 'Is ZionBuild for small to medium contractors?', 'answer' => 'Yes. ZionBuild is designed for teams managing everything from a single custom residential build to mid-size commercial contracting businesses handling multiple simultaneous projects. It is deliberately simple so site foremen can use it with zero technical training.'],
        ['question' => 'Can I manage multiple construction projects?', 'answer' => 'Absolutely. The Command Center lets management toggle across residential, commercial, or fit-out sites with centralized dashboards, separate worker pools, and isolated budget ledgers.'],
        ['question' => 'Does ZionBuild handle payroll, vale and cash advances?', 'answer' => 'Yes, this is built specifically for Philippine site workflows. Foremen record cash advances (vale) in seconds. When weekly payroll is generated, all outstanding advances and attendance records are automatically reconciled into net payouts.'],
        ['question' => 'Can I track budgets and expenses?', 'answer' => 'Yes. You can import your initial Bill of Quantities (BOQ) or budget categories (Structural, Architectural, MEPFS) and log site hardware purchases or sub-contractor billings in real time.'],
        ['question' => 'Can I see daily progress photos?', 'answer' => 'Yes. Site engineers and foremen upload high-resolution photos with GPS tags and timestamps straight from their mobile phones. Photos are organized chronologically and tagged by structural trade.'],
        ['question' => 'Can homeowners monitor their projects?', 'answer' => 'Yes. Homeowners receive dedicated, read-only portal access where they can inspect daily time-stamped site photos, monitor overall milestone completion percentages, and review official progress billing packages without seeing confidential contractor cost markups.'],
        ['question' => 'How much does ZionBuild cost?', 'answer' => 'Pricing is tailored after a short project assessment and demo, depending on your active project count and workforce volume. This ensures you only pay for the scale your business actually operates.'],
        ['question' => 'Can I request a live demo?', 'answer' => 'Yes! Click the "Book a Free Demo" button to schedule a personalized walkthrough with our Philippine technical specialists. We can walk through your real site blueprints and workflow.'],
    ];
@endphp

<section data-track-section="FAQ" id="faq" class="w-full scroll-mt-20 bg-surface-container-low py-20">
    <div class="mx-auto max-w-4xl px-margin-sm lg:px-margin">
        <div class="mx-auto mb-14 max-w-3xl space-y-2 text-center">
            <span class="font-label-md text-label-md font-bold tracking-wider text-primary uppercase">Assistance</span>
            <h2 class="font-headline-lg text-headline-lg font-extrabold text-on-surface">
                Frequently asked questions.
            </h2>
            <p class="font-body-lg text-body-lg text-on-surface-variant">
                Clear answers about adopting ZionBuild for your construction firm or home build.
            </p>
        </div>

        <div class="space-y-3" data-faq>
            @foreach ($faqs as $faq)
                <div class="overflow-hidden rounded-2xl bg-surface-container-lowest shadow-xs">
                    <button
                        type="button"
                        id="faq-toggle-{{ $loop->index }}"
                        aria-expanded="false"
                        aria-controls="faq-answer-{{ $loop->index }}"
                        data-faq-toggle
                        class="group flex w-full items-center justify-between gap-4 p-6 text-left font-title-md text-title-md font-bold text-on-surface transition-colors hover:text-primary"
                    >
                        <span>{{ $faq['question'] }}</span>
                        <span class="material-symbols-outlined shrink-0 text-[24px] text-secondary transition-transform group-aria-expanded:rotate-180">expand_more</span>
                    </button>
                    <div id="faq-answer-{{ $loop->index }}" role="region" aria-labelledby="faq-toggle-{{ $loop->index }}" class="hidden px-6 pb-6 font-body-md text-body-md text-on-surface-variant">
                        {{ $faq['answer'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
