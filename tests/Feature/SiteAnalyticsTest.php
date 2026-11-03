<?php

use App\Filament\Pages\SiteAnalytics;
use App\Filament\Resources\DemoBookings\Pages\ManageDemoBookings;
use App\Filament\Widgets\Analytics\AnalyticsOverview;
use App\Filament\Widgets\Analytics\BookingsBySource;
use App\Filament\Widgets\Analytics\BusiestTimes;
use App\Filament\Widgets\Analytics\ConversionFunnel;
use App\Filament\Widgets\Analytics\DemoDepth;
use App\Filament\Widgets\Analytics\FaqQuestions;
use App\Filament\Widgets\Analytics\FormDropOff;
use App\Filament\Widgets\Analytics\SectionEngagement;
use App\Filament\Widgets\Analytics\TopClicks;
use App\Filament\Widgets\Analytics\TrafficSources;
use App\Filament\Widgets\Analytics\VisitorLocations;
use App\Mail\WeeklyAnalyticsReport;
use App\Models\DemoBooking;
use App\Models\DemoLead;
use App\Models\SiteEvent;
use App\Models\SiteVisit;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

uses(RefreshDatabase::class);

const CHROME_ON_ANDROID = 'Mozilla/5.0 (Linux; Android 14; Pixel 8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Mobile Safari/537.36';

/**
 * @return array<string, mixed>
 */
function eventBatch(array $overrides = []): array
{
    return [
        'visitor_id' => '9b2f6a3e-5c1d-4e8f-9a7b-1c2d3e4f5a6b',
        'visit_id' => '1a2b3c4d-5e6f-4a8b-9c0d-1e2f3a4b5c6d',
        'referrer_host' => 'm.facebook.com',
        'events' => [
            ['type' => 'page_view', 'path' => '/', 'label' => 'ZionBuild AI'],
            ['type' => 'click', 'path' => '/', 'section' => 'Hero', 'label' => 'Book a Free Demo'],
            ['type' => 'section_time', 'path' => '/', 'section' => 'Features', 'label' => 'Features', 'value' => 42],
        ],
        ...$overrides,
    ];
}

test('public pages load the tracker for guests only', function () {
    $this->get('/')->assertSee('name="site-analytics"', false);
    $this->get(route('demo.how-it-works'))->assertSee('name="site-analytics"', false);

    $this->actingAs(User::factory()->create())
        ->get('/')
        ->assertDontSee('name="site-analytics"', false);
});

test('homepage sections and the booking form are tagged for tracking', function () {
    $this->get('/')
        ->assertSee('data-track-section="Hero"', false)
        ->assertSee('data-track-section="Book a Demo"', false)
        ->assertSee('data-track-form="Book a Demo"', false)
        ->assertSee('Your privacy is our #1 priority.');
});

test('a batch opens an anonymous visit and stores its events', function () {
    $this->withHeader('User-Agent', CHROME_ON_ANDROID)
        ->postJson(route('site-events.store'), eventBatch())
        ->assertNoContent();

    $visit = SiteVisit::sole();

    expect($visit)
        ->landing_path->toBe('/')
        ->referrer_host->toBe('m.facebook.com')
        ->device->toBe('mobile')
        ->browser->toBe('Chrome')
        ->is_returning->toBeFalse()
        ->and($visit->events()->pluck('type')->all())->toBe(['page_view', 'click', 'section_time']);
});

test('later batches join the same visit and a new visit from the same browser counts as returning', function () {
    $this->withHeader('User-Agent', CHROME_ON_ANDROID)->postJson(route('site-events.store'), eventBatch());
    $this->withHeader('User-Agent', CHROME_ON_ANDROID)->postJson(route('site-events.store'), eventBatch([
        'events' => [['type' => 'scroll', 'path' => '/', 'value' => 50]],
    ]));

    expect(SiteVisit::count())->toBe(1)
        ->and(SiteEvent::count())->toBe(4);

    $this->withHeader('User-Agent', CHROME_ON_ANDROID)->postJson(route('site-events.store'), eventBatch([
        'visit_id' => '7d6c5b4a-3f2e-4d1c-8b0a-9f8e7d6c5b4a',
    ]));

    expect(SiteVisit::where('uuid', '7d6c5b4a-3f2e-4d1c-8b0a-9f8e7d6c5b4a')->sole()->is_returning)->toBeTrue();
});

test('bots are ignored', function () {
    $this->withHeader('User-Agent', 'Mozilla/5.0 (compatible; Googlebot/2.1)')
        ->postJson(route('site-events.store'), eventBatch())
        ->assertNoContent();

    expect(SiteVisit::count())->toBe(0);
});

test('malformed batches are rejected', function (array $overrides) {
    $this->withHeader('User-Agent', CHROME_ON_ANDROID)
        ->postJson(route('site-events.store'), eventBatch($overrides))
        ->assertUnprocessable();

    expect(SiteVisit::count())->toBe(0);
})->with([
    'unknown event type' => [['events' => [['type' => 'keystroke', 'path' => '/']]]],
    'visit id that is not a uuid' => [['visit_id' => 'abc']],
    'empty batch' => [['events' => []]],
]);

test('visits older than the retention period are pruned with their events', function () {
    SiteEvent::factory()->for(SiteVisit::factory()->state(['last_seen_at' => now()->subMonths(14)]), 'visit')->create();
    SiteEvent::factory()->create();

    $this->artisan('model:prune', ['--model' => SiteVisit::class])->assertSuccessful();

    expect(SiteVisit::count())->toBe(1)
        ->and(SiteEvent::count())->toBe(1);
});

test('admins can open the site analytics page', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(SiteAnalytics::class)
        ->assertOk()
        ->assertSee('Site Analytics')
        ->assertSee('12 months');
});

test('the analytics widgets summarise visits, clicks, reading time and the funnel', function () {
    $this->actingAs(User::factory()->create());

    $visits = SiteVisit::factory()->count(4)->sequence(
        ['referrer_host' => 'www.google.com'],
        ['referrer_host' => 'm.facebook.com'],
        ['referrer_host' => 'l.facebook.com'],
        ['referrer_host' => null],
    )->create(['started_at' => now()->subHour()]);

    $visits->each(fn (SiteVisit $visit) => SiteEvent::factory()->for($visit, 'visit')->create());
    SiteEvent::factory()->for($visits[0], 'visit')->click('Book a Free Demo', 'Hero')->count(3)->create();
    SiteEvent::factory()->for($visits[0], 'visit')->sectionTime('Features', 60)->create();
    SiteEvent::factory()->for($visits[1], 'visit')->sectionTime('Features', 30)->create();
    SiteEvent::factory()->for($visits[1], 'visit')->sectionTime('Book a Demo', 20)->create();
    SiteEvent::factory()->for($visits[1], 'visit')->create(['type' => 'form_start', 'label' => 'Book a Demo']);
    DemoBooking::factory()->create();
    SiteVisit::factory()->create(['started_at' => now()->subDays(40)]);

    Livewire::test(AnalyticsOverview::class, ['pageFilters' => ['period' => '7']])
        ->assertSee('Visitors')
        ->assertSee('25% conversion rate');

    Livewire::test(TopClicks::class, ['pageFilters' => ['period' => '7']])
        ->assertSee('Book a Free Demo')
        ->assertSee('in Hero');

    Livewire::test(SectionEngagement::class, ['pageFilters' => ['period' => '7']])
        ->assertSeeInOrder(['Features', 'Read in 50% of visits', '45s', 'Book a Demo', '20s']);

    Livewire::test(TrafficSources::class, ['pageFilters' => ['period' => '7']])
        ->assertSeeInOrder(['Facebook', '2', 'Google']);

    Livewire::test(ConversionFunnel::class, ['pageFilters' => ['period' => '7']])
        ->assertSeeInOrder(['Visited the site', '4', 'Started filling in the form', '1', 'Booked a demo', '1']);
});

test('the visit location comes from the browser time zone, or Cloudflare headers when present', function () {
    $this->withHeader('User-Agent', CHROME_ON_ANDROID)
        ->postJson(route('site-events.store'), eventBatch(['timezone' => 'Asia/Dubai']));

    expect(SiteVisit::sole())
        ->country->toBe('United Arab Emirates')
        ->timezone->toBe('Asia/Dubai')
        ->region->toBeNull();

    $this->withHeaders(['User-Agent' => CHROME_ON_ANDROID, 'CF-IPCountry' => 'PH', 'CF-IPCity' => 'Cebu City', 'CF-Region' => 'Central Visayas'])
        ->postJson(route('site-events.store'), eventBatch(['visit_id' => '7d6c5b4a-3f2e-4d1c-8b0a-9f8e7d6c5b4a', 'timezone' => 'Not/AZone']));

    expect(SiteVisit::where('uuid', '7d6c5b4a-3f2e-4d1c-8b0a-9f8e7d6c5b4a')->sole())
        ->country->toBe('Philippines')
        ->region->toBe('Cebu City, Central Visayas')
        ->locationName()->toBe('Cebu City, Central Visayas');
});

test('form field focus is recorded by field name only', function () {
    $this->withHeader('User-Agent', CHROME_ON_ANDROID)
        ->postJson(route('site-events.store'), eventBatch([
            'events' => [['type' => 'form_field', 'path' => '/', 'section' => 'Book a Demo', 'label' => 'Email']],
        ]))
        ->assertNoContent();

    expect(SiteEvent::sole())->type->toBe('form_field')->label->toBe('Email');
});

test('the booking form carries the analytics ids and every field is tagged', function () {
    $this->get('/')
        ->assertSee('name="analytics_visitor_id"', false)
        ->assertSee('name="analytics_visit_id"', false)
        ->assertSee('data-track-field="Company info"', false);
});

test('admins see a lead\'s journey before booking', function () {
    $this->actingAs(User::factory()->create());

    $firstVisit = SiteVisit::factory()->create(['referrer_host' => 'www.google.com', 'started_at' => now()->subDays(2), 'device' => 'mobile', 'country' => 'Philippines']);
    $bookingVisit = SiteVisit::factory()->create(['visitor_id' => $firstVisit->visitor_id, 'referrer_host' => 'm.facebook.com', 'started_at' => now()->subMinutes(10), 'device' => 'mobile', 'browser' => 'Chrome', 'country' => 'Philippines']);
    SiteEvent::factory()->for($firstVisit, 'visit')->create(['path' => '/demo/payroll', 'created_at' => now()->subDays(2)]);
    SiteEvent::factory()->for($bookingVisit, 'visit')->sectionTime('Features', 95)->create(['created_at' => now()->subMinutes(5)]);
    SiteEvent::factory()->for($bookingVisit, 'visit')->click('How much does it cost?', 'FAQ')->create(['created_at' => now()->subMinutes(4)]);
    $booking = DemoBooking::factory()->create(['visitor_id' => $firstVisit->visitor_id, 'site_visit_id' => $bookingVisit->id]);

    Livewire::test(ManageDemoBookings::class)
        ->assertTableColumnStateSet('source', 'Facebook', $booking)
        ->mountAction(TestAction::make('view')->table($booking))
        ->assertMountedActionModalSee([
            'Visitor journey before booking',
            'First found us via',
            'Google',
            '2 visits',
            'Mobile · Chrome · Philippines',
            'Payroll',
            'Features: 1m 35s',
            'How much does it cost?',
        ]);
});

test('the new analytics cards summarise conversions, drop-offs, FAQ, demo depth, locations and busy times', function () {
    $this->actingAs(User::factory()->create());
    $filters = ['pageFilters' => ['period' => '7']];

    $facebookVisits = SiteVisit::factory()->count(2)->create(['referrer_host' => 'm.facebook.com', 'country' => 'Philippines', 'started_at' => now()->subHour()]);
    $googleVisit = SiteVisit::factory()->create(['referrer_host' => 'www.google.com', 'country' => 'Philippines', 'started_at' => now()->subHour()]);
    $abroadVisit = SiteVisit::factory()->create(['referrer_host' => null, 'country' => 'United Arab Emirates', 'started_at' => now()->subHour()]);

    DemoBooking::factory()->create(['site_visit_id' => $facebookVisits[0]->id]);
    DemoBooking::factory()->create(['site_visit_id' => null]);

    foreach ([$facebookVisits[0], $facebookVisits[1], $googleVisit] as $visit) {
        SiteEvent::factory()->for($visit, 'visit')->create(['type' => 'form_start', 'label' => 'Book a Demo']);
    }
    SiteEvent::factory()->for($facebookVisits[1], 'visit')->create(['type' => 'form_field', 'section' => 'Book a Demo', 'label' => 'Name']);
    SiteEvent::factory()->for($facebookVisits[1], 'visit')->create(['type' => 'form_field', 'section' => 'Book a Demo', 'label' => 'Company info']);
    SiteEvent::factory()->for($googleVisit, 'visit')->create(['type' => 'form_field', 'section' => 'Book a Demo', 'label' => 'Company info']);

    SiteEvent::factory()->for($googleVisit, 'visit')->click('How much does it cost?', 'FAQ')->count(2)->create();
    SiteEvent::factory()->for($googleVisit, 'visit')->click('Book a Free Demo', 'Hero')->create();

    foreach (['/demo/how-it-works', '/demo', '/demo/field-reports', '/demo/payroll'] as $path) {
        SiteEvent::factory()->for($abroadVisit, 'visit')->create(['path' => $path]);
    }
    SiteEvent::factory()->for($googleVisit, 'visit')->create(['path' => '/demo/how-it-works']);
    DemoLead::factory()->create();

    Livewire::test(BookingsBySource::class, $filters)
        ->assertSeeInOrder(['Facebook', '50% of 2 visits converted', 'Not tracked']);

    Livewire::test(FormDropOff::class, $filters)
        ->assertSee('3 started the form and 1 booked')
        ->assertSeeInOrder(['Stopped at: Company info', '100% of drop-offs', '2']);

    Livewire::test(FaqQuestions::class, $filters)->assertSeeInOrder(['How much does it cost?', 'Opened in 1 visit', '2']);
    Livewire::test(TopClicks::class, $filters)->assertSee('Book a Free Demo')->assertDontSee('How much does it cost?');

    Livewire::test(DemoDepth::class, $filters)
        ->assertSeeInOrder(['Opened the live demo', '2', 'Explored 4 or more modules', '1', 'Unlocked the full demo', '1']);

    Livewire::test(VisitorLocations::class, $filters)->assertSeeInOrder(['Philippines', '3', 'United Arab Emirates', '1']);

    Livewire::test(BusiestTimes::class, $filters)->assertSee('Busiest days and hours')->assertSee('(4 visits)');
});

test('the weekly report emails the last 7 days of analytics', function () {
    Mail::fake();
    SiteEvent::factory()->for(SiteVisit::factory()->create(['referrer_host' => 'm.facebook.com', 'started_at' => now()->subDay()]), 'visit')->create();

    $this->artisan('analytics:weekly-report')->assertSuccessful();

    Mail::assertSent(WeeklyAnalyticsReport::class, fn (WeeklyAnalyticsReport $mail): bool => $mail->hasTo('rebalobor@gmail.com'));

    (new WeeklyAnalyticsReport)
        ->assertSeeInHtml('Your website this week')
        ->assertSeeInHtml('Where visitors come from')
        ->assertSeeInHtml('Facebook')
        ->assertSeeInHtml(SiteAnalytics::getUrl());
});
