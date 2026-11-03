<?php

use App\Filament\Resources\DemoBookings\Pages\ManageDemoBookings;
use App\Mail\DemoBookingReceived;
use App\Models\DemoBooking;
use App\Models\SiteVisit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

uses(RefreshDatabase::class);

/**
 * @return array<string, string>
 */
function bookingForm(array $overrides = []): array
{
    return [
        'name' => 'Maria Santos',
        'contact_number' => '0917 123 4567',
        'email' => 'maria@santosbuilders.ph',
        'company_info' => 'Santos Builders, 3 residential projects in Cavite',
        ...$overrides,
    ];
}

beforeEach(function () {
    Mail::fake();
});

test('the homepage shows the book a demo form', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('id="demo"', false)
        ->assertSee('action="'.route('demo-booking.store').'"', false)
        ->assertSee('name="contact_number"', false)
        ->assertSee('name="company_info"', false)
        ->assertSee('Book a Demo');
});

test('a booking is saved, emailed and thanked', function () {
    $this->post(route('demo-booking.store'), bookingForm())
        ->assertRedirect(url('/#demo'))
        ->assertSessionHas('demo_booked', 'Maria Santos');

    $this->assertDatabaseHas(DemoBooking::class, [
        'name' => 'Maria Santos',
        'contact_number' => '09171234567',
        'email' => 'maria@santosbuilders.ph',
        'status' => 'new',
    ]);

    Mail::assertSent(DemoBookingReceived::class, function (DemoBookingReceived $mail): bool {
        return $mail->hasTo('rebalobor@gmail.com')
            && $mail->hasReplyTo('maria@santosbuilders.ph')
            && $mail->booking->name === 'Maria Santos';
    });

    $this->withSession(['demo_booked' => 'Maria Santos'])
        ->get('/')
        ->assertSee('Thank you, Maria Santos!')
        ->assertDontSee('name="company_info"', false);
});

test('the email lists every detail the visitor gave', function () {
    $booking = DemoBooking::factory()->create([
        'name' => 'Maria Santos',
        'contact_number' => '09171234567',
        'email' => null,
        'company_info' => 'Santos Builders, 3 residential projects in Cavite',
    ]);

    (new DemoBookingReceived($booking))
        ->assertHasSubject('New demo booking: Maria Santos')
        ->assertSeeInHtml('09171234567')
        ->assertSeeInHtml('Santos Builders, 3 residential projects in Cavite')
        ->assertSeeInHtml(route('filament.zpanel.resources.demo-bookings.index'));
});

test('a booking is linked to the analytics visit it came from', function () {
    $visit = SiteVisit::factory()->create();

    $this->post(route('demo-booking.store'), bookingForm([
        'analytics_visitor_id' => $visit->visitor_id,
        'analytics_visit_id' => $visit->uuid,
    ]));

    expect(DemoBooking::sole())
        ->visitor_id->toBe($visit->visitor_id)
        ->site_visit_id->toBe($visit->id);
});

test('either a contact number or an email is enough', function (array $overrides) {
    $this->post(route('demo-booking.store'), bookingForm($overrides))
        ->assertSessionHasNoErrors();

    expect(DemoBooking::count())->toBe(1);
})->with([
    'contact number only' => [['email' => '']],
    'email only' => [['contact_number' => '']],
]);

test('the form rejects invalid details', function (array $overrides, string $field) {
    $this->post(route('demo-booking.store'), bookingForm($overrides))
        ->assertRedirect(url('/#demo'))
        ->assertSessionHasErrors($field);

    expect(DemoBooking::count())->toBe(0);
    Mail::assertNothingSent();
})->with([
    'no way to reach them' => [['contact_number' => '', 'email' => ''], 'contact_number'],
    'missing name' => [['name' => ''], 'name'],
    'missing company info' => [['company_info' => ''], 'company_info'],
    'invalid email' => [['email' => 'not-an-email'], 'email'],
    'invalid phone' => [['contact_number' => 'call me'], 'contact_number'],
    'honeypot filled' => [['website' => 'https://spam.example'], 'website'],
]);

test('the booking is kept even when the email fails to send', function () {
    Mail::shouldReceive('to')->andThrow(new RuntimeException('SMTP down'));

    $this->post(route('demo-booking.store'), bookingForm())
        ->assertRedirect(url('/#demo'));

    expect(DemoBooking::count())->toBe(1);
});

test('admins can list bookings and update their follow-up status', function () {
    $this->actingAs(User::factory()->create());
    $bookings = DemoBooking::factory()->count(3)->create();

    Livewire::test(ManageDemoBookings::class)
        ->assertCanSeeTableRecords($bookings)
        ->callTableAction('edit', $bookings->first(), ['status' => 'contacted', 'notes' => 'Called, demo on Friday'])
        ->assertHasNoTableActionErrors();

    expect($bookings->first()->refresh())
        ->status->toBe('contacted')
        ->notes->toBe('Called, demo on Friday');
});
