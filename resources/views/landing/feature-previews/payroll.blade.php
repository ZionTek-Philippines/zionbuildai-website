@php
    $payrollEntries = [
        ['worker' => 'Lead Mason: R. Santos', 'gross' => '₱5,400', 'vale' => '₱1,500', 'net' => '₱3,900'],
        ['worker' => 'Steel Man: J. Dizon', 'gross' => '₱4,800', 'vale' => '₱500', 'net' => '₱4,300'],
    ];
@endphp

<div class="flex flex-wrap items-center justify-between gap-3 rounded-xl bg-surface-container-low p-4">
    <div>
        <p class="font-title-md text-title-md font-bold text-on-surface">Friday Weekly Payroll Ready</p>
        <p class="font-body-sm text-body-sm text-on-surface-variant">24 workers • 6 days clocked • GPS geofenced</p>
    </div>
    <span class="rounded-full bg-primary-fixed px-3 py-1 font-label-sm text-label-sm font-bold text-on-primary-fixed">Auto-Deducted Vale</span>
</div>

<div class="space-y-2">
    @foreach ($payrollEntries as $entry)
        <div class="flex items-center justify-between gap-3 rounded-lg bg-surface-container-low p-3">
            <div>
                <p class="font-body-md text-body-md font-bold text-on-surface">{{ $entry['worker'] }}</p>
                <p class="font-label-sm text-label-sm text-on-surface-variant">Gross: {{ $entry['gross'] }} | Vale Deducted: {{ $entry['vale'] }}</p>
            </div>
            <span class="shrink-0 font-title-md text-title-md font-bold text-tertiary">Net: {{ $entry['net'] }}</span>
        </div>
    @endforeach
</div>
