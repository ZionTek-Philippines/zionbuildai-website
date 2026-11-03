<x-mail::message>
# New demo booking

**{{ $booking->name }}** just booked a free demo on the ZionBuild AI website.

<x-mail::table>
| | |
| :-- | :-- |
| **Name** | {{ $booking->name }} |
| **Contact number** | {{ $booking->contact_number ?? '—' }} |
| **Email** | {{ $booking->email ?? '—' }} |
| **Received** | {{ $booking->created_at->timezone('Asia/Manila')->format('M d, Y g:i A') }} |
</x-mail::table>

**About their company**

<x-mail::panel>
{{ $booking->company_info }}
</x-mail::panel>

<x-mail::button :url="route('filament.zpanel.resources.demo-bookings.index')">
Open in Admin Panel
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
