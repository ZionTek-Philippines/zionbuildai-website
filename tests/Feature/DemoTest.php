<?php

use App\Http\Middleware\EnsureDemoIsUnlocked;

// Content tests run as a visitor who has already unlocked the gated modules (see DemoLeadTest for the gate itself).
beforeEach(fn () => $this->withSession([EnsureDemoIsUnlocked::SESSION_KEY => true]));

test('the how it works demo shows the three steps from site to laptop', function () {
    $this->get(route('demo.how-it-works'))
        ->assertOk()
        ->assertSeeInOrder(['Time-in', 'Time-out', 'Field Reports'])
        ->assertSee('Ano ginawa mo today?')
        ->assertSee('Second-floor slab rebar tying, about 60% done.')
        ->assertSee('data-tour="step-contractor"', false);
});

test('the how it works tour ends with a link to the command center', function () {
    $steps = tourSteps($this->get(route('demo.how-it-works'))->getContent());

    expect(end($steps)['next']['url'])->toBe(route('demo.command-center'));
});

test('the command center demo renders the sample project', function () {
    $response = $this->get(route('demo.command-center'));

    $response->assertOk()
        ->assertSee('Ong Residential')
        ->assertSee('₱734,950')
        ->assertSee('data-demo-guide', false)
        ->assertSee('data-tour="net-cash"', false);
});

test('the field reports demo renders work logs and progress updates', function () {
    $this->get(route('demo.field-reports'))
        ->assertOk()
        ->assertSee('Second-floor slab rebar tying, about 60% done.')
        ->assertSee('Ground-floor CHB walls completed. Second-floor slab preparation started.')
        ->assertSee('Ano ginawa mo today?')
        ->assertSee('data-shared-count>4<', false);
});

test('the ai project monitoring demo renders progress, the report and ai photo analysis', function () {
    $this->get(route('demo.ai-project-monitoring'))
        ->assertOk()
        ->assertSee('40.4%')
        ->assertSee('₱4,284,000')
        ->assertSee('Accept match')
        ->assertSee('BETA');
});

test('the finances demo renders budget vs actual, expenses and bank records', function () {
    $this->get(route('demo.finances'))
        ->assertOk()
        ->assertSee('₱7,140,000')
        ->assertSee('₱3,345,050')
        ->assertSee('₱3,730,700')
        ->assertSee('EXP-0000112')
        ->assertSee('Not connected to your bank');
});

test('the payroll demo adds up to the command center payroll', function () {
    $this->get(route('demo.payroll'))
        ->assertOk()
        ->assertSee('PYR-0000023')
        ->assertSee('₱58,400')
        ->assertSee('₱52,900')
        ->assertSee('Mateo Cruz');
});

test('the client billing demo renders payments and the client portal', function () {
    $this->get(route('demo.client-billing'))
        ->assertOk()
        ->assertSee('₱4,080,000')
        ->assertSee('CP-0000003')
        ->assertSee('Hi, Ong family')
        ->assertSee('never shown to the client');
});

test('the payroll tour ends with a link to client billing', function () {
    $steps = tourSteps($this->get(route('demo.payroll'))->getContent());

    expect(end($steps)['next']['url'])->toBe(route('demo.client-billing'));
});

test('the finances tour ends with a link to payroll', function () {
    $steps = tourSteps($this->get(route('demo.finances'))->getContent());

    expect(end($steps)['next']['url'])->toBe(route('demo.payroll'));
});

test('the ai project monitoring tour ends with a link to finances', function () {
    $steps = tourSteps($this->get(route('demo.ai-project-monitoring'))->getContent());

    expect(end($steps)['next']['url'])->toBe(route('demo.finances'));
});

test('the field reports tour ends with a link to ai project monitoring', function () {
    $steps = tourSteps($this->get(route('demo.field-reports'))->getContent());

    expect(end($steps)['next']['url'])->toBe(route('demo.ai-project-monitoring'));
});

test('the command center tour ends with a link to field reports', function () {
    $steps = tourSteps($this->get(route('demo.command-center'))->getContent());

    expect(end($steps)['next']['url'])->toBe(route('demo.field-reports'));
});

test('every walkthrough step points at a section on the page', function (string $routeName) {
    $html = $this->get(route($routeName))->getContent();

    $steps = tourSteps($html);

    expect($steps)->not->toBeEmpty();

    collect($steps)->pluck('target')->filter()->each(
        fn (string $target) => expect($html)->toContain('data-tour="'.$target.'"')
    );
})->with(['demo.how-it-works', 'demo.command-center', 'demo.field-reports', 'demo.ai-project-monitoring', 'demo.finances', 'demo.payroll', 'demo.client-billing']);

test('every walkthrough step has english and taglish copy', function (string $routeName) {
    $steps = tourSteps($this->get(route($routeName))->getContent());

    foreach ($steps as $step) {
        foreach (['en', 'tl'] as $lang) {
            expect($step[$lang]['title'] ?? null)->toBeString()->not->toBeEmpty()
                ->and($step[$lang]['body'] ?? null)->toBeString()->not->toBeEmpty();
        }

        expect(isset($step['en']['example']))->toBe(isset($step['tl']['example']));
    }
})->with(['demo.how-it-works', 'demo.command-center', 'demo.field-reports', 'demo.ai-project-monitoring', 'demo.finances', 'demo.payroll', 'demo.client-billing']);

test('the demo pages offer the english and taglish toggle', function () {
    $this->get(route('demo.command-center'))
        ->assertSee('data-demo-lang-set="en"', false)
        ->assertSee('data-demo-lang-set="tl"', false);
});

test('the homepage offers the try it demo', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('data-try-it', false)
        ->assertSee(route('demo.how-it-works'));
});

/**
 * @return list<array{target: ?string, en: array{title: string, body: string, example?: string}, tl: array{title: string, body: string, example?: string}}>
 */
function tourSteps(string $html): array
{
    preg_match('/data-steps="([^"]+)"/', $html, $matches);

    return json_decode(html_entity_decode($matches[1]), true);
}
