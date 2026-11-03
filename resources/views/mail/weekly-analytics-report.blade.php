<x-mail::message>
# Your website this week

Here's how the ZionBuild AI website did over the last 7 days.

<x-mail::table>
| | |
| :-- | --: |
| **Visitors** | {{ number_format($metrics['visitors']) }}{{ $visitorsChange ? " ({$visitorsChange})" : '' }} |
| **Visits** | {{ number_format($metrics['visits']) }} |
| **Opened the live demo** | {{ number_format($metrics['demo_opens']) }} |
| **Demo bookings** | {{ number_format($metrics['bookings']) }} ({{ $conversionRate }} conversion) |
</x-mail::table>

@foreach ($cards as $heading => $rows)
**{{ $heading }}**

<x-mail::table>
| | |
| :-- | --: |
@foreach ($rows as $row)
| {{ $row['label'] }} | {{ $row['display'] }} |
@endforeach
</x-mail::table>

@endforeach
@if (empty($cards))
No visitor activity was recorded this week.
@endif

<x-mail::button :url="$dashboardUrl">
Open Site Analytics
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
