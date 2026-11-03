<?php

use App\Filament\Pages\VisualAnalytics;
use App\Models\DemoBooking;
use App\Models\SiteEvent;
use App\Models\SiteVisit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());

    [$first, $second] = SiteVisit::factory()->count(2)->create(['started_at' => now()->subHour()])->all();

    foreach ([$first, $second] as $visit) {
        SiteEvent::factory()->for($visit, 'visit')->create();
        SiteEvent::factory()->for($visit, 'visit')->click('Try it! Live Demo', 'Hero')->create();
        SiteEvent::factory()->for($visit, 'visit')->create(['type' => 'scroll', 'value' => 50]);
        SiteEvent::factory()->for($visit, 'visit')->create(['type' => 'form_field', 'section' => 'Book a Demo', 'label' => 'Name']);
    }

    SiteEvent::factory()->for($first, 'visit')->click('Try it! Live Demo', 'Hero')->create();
    SiteEvent::factory()->for($first, 'visit')->sectionTime('Features', 90)->create();
    SiteEvent::factory()->for($second, 'visit')->sectionTime('Features', 30)->create();
    SiteEvent::factory()->for($second, 'visit')->click('Next', null)->create(['path' => '/demo/how-it-works']);
    SiteEvent::factory()->for($second, 'visit')->create(['type' => 'form_field', 'section' => 'Book a Demo', 'label' => 'Email']);
    DemoBooking::factory()->create(['site_visit_id' => $first->id]);

    SiteEvent::factory()->for(SiteVisit::factory()->create(['started_at' => now()->subDays(60)]), 'visit')
        ->click('Try it! Live Demo', 'Hero')->create(['created_at' => now()->subDays(60)]);
});

test('the page shows the live homepage with its stats embedded for the overlay', function () {
    Livewire::test(VisualAnalytics::class)
        ->assertOk()
        ->assertSee('data-visual-frame', false)
        ->assertSee('src="'.url('/').'"', false)
        ->assertSee('data-visual-data', false)
        ->assertSee('Try it! Live Demo')
        ->assertSee('Click hotspots');
});

test('the overlay data covers homepage clicks, reading time, scroll depth and form drop-off for the period', function () {
    $data = Livewire::test(VisualAnalytics::class, ['period' => '7'])->instance()->getVisualData();

    expect($data['homepageVisits'])->toBe(2)
        ->and($data['clicks'])->toHaveCount(1)
        ->and($data['clicks'][0])->toMatchArray(['label' => 'Try it! Live Demo', 'section' => 'Hero', 'clicks' => 3, 'visits' => 2])
        ->and($data['clicks'][0]['url'])->toContain('site-visits')
        ->and(collect($data['sections'])->firstWhere('name', 'Features'))->toMatchArray(['averageSeconds' => 60, 'readers' => 2])
        ->and(collect($data['scroll'])->pluck('visits', 'milestone')->all())->toBe([25 => 2, 50 => 2, 75 => 0, 100 => 0])
        ->and(collect($data['fields'])->keyBy('label')->map(fn (array $field): array => [$field['focused'], $field['stopped']])->all())
        ->toBe(['Email' => [1, 1], 'Name' => [2, 0]]);
});

test('a longer period includes older activity', function () {
    $data = Livewire::test(VisualAnalytics::class, ['period' => '90'])->instance()->getVisualData();

    expect($data['clicks'][0]['clicks'])->toBe(4);
});
