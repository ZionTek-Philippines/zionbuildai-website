<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDemoBookingRequest;
use App\Mail\DemoBookingReceived;
use App\Models\DemoBooking;
use App\Models\SiteVisit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Throwable;

class DemoBookingController extends Controller
{
    /**
     * Save the booking and email it to the sales inbox.
     *
     * A mail failure is reported but never loses the booking: it is already saved and visible in the admin panel.
     */
    public function store(StoreDemoBookingRequest $request): RedirectResponse
    {
        $booking = DemoBooking::create([
            ...$request->safe()->only(['name', 'contact_number', 'email', 'company_info']),
            'visitor_id' => $request->validated('analytics_visitor_id'),
            'site_visit_id' => SiteVisit::where('uuid', $request->validated('analytics_visit_id'))->value('id'),
        ]);

        try {
            Mail::to(config('services.demo_bookings.notify'))->send(new DemoBookingReceived($booking));
        } catch (Throwable $exception) {
            report($exception);
        }

        return redirect()->to(url('/#demo'))->with('demo_booked', $booking->name);
    }
}
