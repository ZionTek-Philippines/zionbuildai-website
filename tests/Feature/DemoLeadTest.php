<?php

use App\Http\Middleware\EnsureDemoIsUnlocked;
use App\Models\DemoLead;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * @return array<string, string>
 */
function leadForm(array $overrides = []): array
{
    return [
        'name' => 'Maria Santos',
        'mobile' => '0917 123 4567',
        'company' => 'Santos Builders',
        'role' => 'contractor',
        'unlocked_from' => 'demo.finances',
        ...$overrides,
    ];
}

test('gated modules show only their top section, with the unlock form below it', function (string $routeName, string $teaserFigure, string $hiddenContent) {
    $this->get(route($routeName))
        ->assertOk()
        ->assertSee($teaserFigure)
        ->assertSee('Unlock the full demo')
        ->assertSee('name="mobile"', false)
        ->assertSeeInOrder([$teaserFigure, 'Unlock the full demo'])
        ->assertDontSee($hiddenContent)
        ->assertDontSee('data-demo-guide', false);
})->with([
    ['demo.finances', '₱7,140,000', 'Spending is 5 points ahead of progress'],
    ['demo.payroll', '₱52,900', 'Mateo Cruz'],
    ['demo.client-billing', '₱10,200,000', 'CP-0000003'],
]);

test('the other tabs of a locked module cannot be opened', function () {
    $this->get(route('demo.finances'))
        ->assertSee('data-demo-tab="expenses" aria-selected="false" disabled', false);
});

test('open modules need no sign-up', function (string $routeName) {
    $this->get(route($routeName))
        ->assertOk()
        ->assertDontSee('Unlock the full demo');
})->with(['demo.how-it-works', 'demo.command-center', 'demo.field-reports', 'demo.ai-project-monitoring']);

test('submitting the form saves the lead and unlocks every gated module', function () {
    $this->from(route('demo.finances'))
        ->post(route('demo.unlock'), leadForm())
        ->assertRedirect(route('demo.finances'))
        ->assertSessionHas(EnsureDemoIsUnlocked::SESSION_KEY, true);

    $this->assertDatabaseHas(DemoLead::class, [
        'name' => 'Maria Santos',
        'mobile' => '09171234567',
        'company' => 'Santos Builders',
        'role' => 'contractor',
        'unlocked_from' => 'demo.finances',
    ]);

    $this->get(route('demo.finances'))->assertSee('₱7,140,000');
    $this->get(route('demo.payroll'))->assertSee('₱52,900');
    $this->get(route('demo.client-billing'))->assertSee('CP-0000003');
});

test('the form rejects invalid details', function (array $overrides, string $field) {
    $this->post(route('demo.unlock'), leadForm($overrides))
        ->assertSessionHasErrors($field)
        ->assertSessionMissing(EnsureDemoIsUnlocked::SESSION_KEY);

    expect(DemoLead::count())->toBe(0);
})->with([
    'mobile that is not a PH number' => [['mobile' => '12345'], 'mobile'],
    'missing name' => [['name' => ''], 'name'],
    'unknown role' => [['role' => 'competitor'], 'role'],
]);

test('submissions that fill the honeypot are rejected', function () {
    $this->post(route('demo.unlock'), leadForm(['website' => 'https://spam.example']))
        ->assertSessionHasErrors('website');

    expect(DemoLead::count())->toBe(0);
});

test('locked modules are marked in the module tabs until unlocked', function () {
    $this->get(route('demo.command-center'))->assertSee('Unlock with a short form');

    $this->withSession([EnsureDemoIsUnlocked::SESSION_KEY => true])
        ->get(route('demo.command-center'))
        ->assertDontSee('Unlock with a short form');
});

test('turning the unlock form off opens every gated module without asking for details', function (string $routeName, string $hiddenContent) {
    config(['app.demo_unlock_form_enabled' => false]);

    $this->get(route($routeName))
        ->assertOk()
        ->assertSee($hiddenContent)
        ->assertSee('data-demo-guide', false)
        ->assertDontSee('Unlock the full demo')
        ->assertDontSee('name="mobile"', false)
        ->assertDontSee('Unlock with a short form');
})->with([
    ['demo.finances', 'Spending is 5 points ahead of progress'],
    ['demo.payroll', 'Mateo Cruz'],
    ['demo.client-billing', 'CP-0000003'],
]);
