@php
    $suiteFeatures = [
        ['icon' => 'dashboard', 'title' => 'Command Center', 'description' => 'Progress, budget, workforce and updates for every project at a glance.'],
        ['icon' => 'how_to_reg', 'title' => 'Worker Attendance', 'description' => 'Daily attendance and history per project, ready for payroll.'],
        ['icon' => 'photo_camera', 'title' => 'Daily Progress Photos', 'description' => 'Accomplishment photos and descriptions sent straight from the site.'],
        ['icon' => 'auto_awesome', 'title' => 'AI Photo Analysis', 'description' => 'AI matches site photos to milestone items. A person approves every change.'],
        ['icon' => 'savings', 'title' => 'Budget Management', 'description' => 'Budget vs actual, cost breakdowns and remaining budget per project.'],
        ['icon' => 'receipt', 'title' => 'Expense Management', 'description' => 'Categorized, project-based expenses with receipt records.'],
        ['icon' => 'payments', 'title' => 'Payroll, Vale & Cash Advances', 'description' => 'Attendance-based payroll with vale, cash advances and deductions.'],
        ['icon' => 'warehouse', 'title' => 'Inventory', 'description' => 'Materials received, used and remaining, tracked per project.'],
        ['icon' => 'construction', 'title' => 'Equipment', 'description' => 'Equipment records, assignments, availability and usage.'],
        ['icon' => 'flag', 'title' => 'Milestones & Scheduling', 'description' => 'Phases, target schedules and milestone completion in one timeline.'],
        ['icon' => 'trending_up', 'title' => 'S-Curve', 'description' => 'Planned vs actual progress to see if you are ahead, on time or delayed.'],
        ['icon' => 'calculate', 'title' => 'BOQ Builder', 'description' => 'Work items, quantities, and material and labor costs in structured estimates.'],
        ['icon' => 'shopping_cart_checkout', 'title' => 'Procurement & Purchase Orders', 'description' => 'Purchase orders, purchasing status and procurement history.'],
        ['icon' => 'description', 'title' => 'Accomplishment Reports', 'description' => 'Progress summaries backed by site photos and work records.'],
        ['icon' => 'supervised_user_circle', 'title' => 'Homeowner Portal', 'description' => 'Homeowners see the progress, photos and reports you authorize.'],
        ['icon' => 'account_balance', 'title' => 'Bank & Financial Records', 'description' => 'Record bank accounts, cash in and out, payments and disbursements.'],
        ['icon' => 'folder_special', 'title' => 'Document Management', 'description' => 'Project files organized and stored in one central place.'],
        ['icon' => 'domain', 'title' => 'Multi-Project Management', 'description' => 'One, five or ten projects, each with its own budget, team and records.'],
    ];
@endphp

<section data-track-section="All-in-One Suite" class="w-full bg-surface-container-low py-20">
    <div class="mx-auto max-w-7xl px-margin-sm lg:px-margin">
        <div class="mx-auto mb-14 max-w-3xl space-y-2 text-center">
            <span class="font-label-md text-label-md font-bold tracking-wider text-primary uppercase">Complete Operational Suite</span>
            <h2 class="font-headline-lg text-headline-lg font-extrabold text-on-surface">
                Everything you need, in one place.
            </h2>
            <p class="font-body-lg text-body-lg text-on-surface-variant">
                Every ZionBuild feature, from the site to the office, in a single system.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($suiteFeatures as $suiteFeature)
                <div class="flex items-start gap-4 rounded-2xl bg-surface-container-lowest p-5 shadow-xs transition-transform hover:-translate-y-0.5">
                    <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-surface-container text-primary">
                        <span class="material-symbols-outlined text-[22px]">{{ $suiteFeature['icon'] }}</span>
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-title-md text-title-md font-bold text-on-surface">{{ $suiteFeature['title'] }}</h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">{{ $suiteFeature['description'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
