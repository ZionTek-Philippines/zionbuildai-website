<?php

use App\Filament\Resources\DemoBookings\Pages\ManageDemoBookings;
use App\Filament\Resources\DemoLeads\Pages\ManageDemoLeads;
use App\Filament\Resources\SiteVisits\Pages\ManageSiteVisits;
use App\Filament\Widgets\Analytics\BookingsBySource;
use App\Filament\Widgets\Analytics\ConversionFunnel;
use App\Filament\Widgets\Analytics\DemoDepth;
use App\Filament\Widgets\Analytics\FaqQuestions;
use App\Filament\Widgets\Analytics\FormDropOff;
use App\Filament\Widgets\Analytics\SectionEngagement;
use App\Filament\Widgets\Analytics\TopClicks;
use App\Filament\Widgets\Analytics\TopPages;
use App\Filament\Widgets\Analytics\TrafficSources;
use App\Filament\Widgets\Analytics\VisitorLocations;
use App\Models\DemoBooking;
use App\Models\DemoLead;
use App\Models\SiteEvent;
use App\Models\SiteVisit;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());

    $facebook = SiteVisit::factory()->count(2)->create(['referrer_host' => 'm.facebook.com', 'utm_source' => null, 'country' => 'Philippines', 'region' => null, 'started_at' => now()->subHours(2)]);
    $google = SiteVisit::factory()->create(['referrer_host' => 'www.google.com', 'utm_source' => null, 'country' => 'Philippines', 'region' => 'Cebu City, Central Visayas', 'started_at' => now()->subHours(2)]);
    $abroad = SiteVisit::factory()->create(['referrer_host' => null, 'utm_source' => null, 'country' => 'United Arab Emirates', 'region' => null, 'started_at' => now()->subHours(2)]);
    SiteVisit::factory()->create(['referrer_host' => 'm.facebook.com', 'started_at' => now()->subDays(60)]);

    foreach ([...$facebook, $google, $abroad] as $visit) {
        SiteEvent::factory()->for($visit, 'visit')->create();
    }

    foreach ([$facebook[0], $facebook[1], $google] as $visit) {
        SiteEvent::factory()->for($visit, 'visit')->create(['type' => 'scroll', 'value' => 50]);
        SiteEvent::factory()->for($visit, 'visit')->sectionTime('Book a Demo', 15)->create();
        SiteEvent::factory()->for($visit, 'visit')->create(['type' => 'form_start', 'label' => 'Book a Demo']);
        SiteEvent::factory()->for($visit, 'visit')->create(['type' => 'form_field', 'section' => 'Book a Demo', 'label' => 'Name']);
    }
    SiteEvent::factory()->for($facebook[1], 'visit')->create(['type' => 'form_field', 'section' => 'Book a Demo', 'label' => 'Company info']);

    SiteEvent::factory()->for($google, 'visit')->sectionTime('Features', 80)->create();
    SiteEvent::factory()->for($google, 'visit')->click('How much does ZionBuild cost?', 'FAQ')->count(2)->create();
    SiteEvent::factory()->for($facebook[1], 'visit')->click('Book a Free Demo', 'Hero')->create();

    foreach (['/demo/how-it-works', '/demo', '/demo/field-reports', '/demo/payroll'] as $path) {
        SiteEvent::factory()->for($abroad, 'visit')->create(['path' => $path]);
    }
    SiteEvent::factory()->for($google, 'visit')->create(['path' => '/demo/how-it-works']);

    $this->booking = DemoBooking::factory()->create(['site_visit_id' => $facebook[0]->id, 'visitor_id' => $facebook[0]->visitor_id]);
    DemoBooking::factory()->create(['site_visit_id' => null]);
    DemoBooking::factory()->create(['site_visit_id' => null, 'created_at' => now()->subDays(60)]);
    DemoLead::factory()->create();
    DemoLead::factory()->create(['created_at' => now()->subDays(60)]);
});

/**
 * Open the list a card row links to, with the row's filters applied, and return how many records it shows.
 */
function recordsBehind(string $url): int
{
    parse_str((string) parse_url($url, PHP_URL_QUERY), $query);

    $page = match (true) {
        str_contains($url, '/demo-bookings') => ManageDemoBookings::class,
        str_contains($url, '/demo-leads') => ManageDemoLeads::class,
        default => ManageSiteVisits::class,
    };

    return Livewire::withQueryParams($query)->test($page)->instance()->getFilteredTableQuery()->count();
}

/**
 * @return list<array<string, mixed>>
 */
function cardRows(string $widget): array
{
    return Livewire::test($widget, ['pageFilters' => ['period' => '7']])->instance()->getRows();
}

test('every row that counts visits, bookings or leads opens a list with exactly that many records', function (string $widget) {
    $rows = cardRows($widget);

    expect($rows)->not->toBeEmpty();

    foreach ($rows as $row) {
        expect($row['url'])->not->toBeNull()
            ->and(recordsBehind($row['url']))->toBe($row['value'], "{$row['label']} in ".class_basename($widget));
    }
})->with([
    ConversionFunnel::class,
    BookingsBySource::class,
    FormDropOff::class,
    DemoDepth::class,
    TrafficSources::class,
    VisitorLocations::class,
]);

test('rows that count clicks, views or reading time open the visits behind them', function (string $widget, string $label, int $visits) {
    $row = collect(cardRows($widget))->firstWhere('label', $label);

    expect(recordsBehind($row['url']))->toBe($visits);
})->with([
    'FAQ question opened twice in one visit' => [FaqQuestions::class, 'How much does ZionBuild cost?', 1],
    'hero button' => [TopClicks::class, 'Book a Free Demo', 1],
    'section read in three visits' => [SectionEngagement::class, 'Book a Demo', 3],
    'demo page viewed in two visits' => [TopPages::class, 'Live demo · How It Works', 2],
]);

test('the period from the analytics page carries into the drill-down list', function () {
    $visitedRow = collect(cardRows(ConversionFunnel::class))->firstWhere('label', 'Visited the site');

    expect($visitedRow['url'])->toContain('filters%5Bperiod%5D%5Bvalue%5D=7')
        ->and(recordsBehind($visitedRow['url']))->toBe(4);
});

test('a visit opens as a plain-English timeline with its booking', function () {
    $visit = $this->booking->visit;

    Livewire::test(ManageSiteVisits::class)
        ->mountAction(TestAction::make('view')->table($visit))
        ->assertMountedActionModalSee([
            'Facebook',
            'Viewed Homepage',
            'Scrolled 50% down the Homepage',
            'Read Book a Demo for 15s',
            'Started the Book a Demo form',
            'Clicked into the "Name" field',
            $this->booking->name,
        ]);
});
