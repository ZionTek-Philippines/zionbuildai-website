<?php

test('turning the demo off hides every Try it! entry point and the popup', function () {
    config(['app.demo_enabled' => false]);

    $this->get('/')
        ->assertOk()
        ->assertSee('hero-command-center.jpg', false)
        ->assertDontSee('data-try-it', false)
        ->assertDontSee('Try it! Live Demo')
        ->assertDontSee('/demo', false);

    $this->get(route('contact'))
        ->assertOk()
        ->assertDontSee('Try the Live Demo')
        ->assertDontSee('data-try-it', false);
});

test('turning the demo off makes every demo page return 404', function (string $path) {
    config(['app.demo_enabled' => false]);

    $this->get($path)->assertNotFound();
})->with(['/demo', '/demo/how-it-works', '/demo/field-reports', '/demo/ai-project-monitoring', '/demo/finances', '/demo/payroll', '/demo/client-billing']);

test('turning the demo off rejects unlock form submissions', function () {
    config(['app.demo_enabled' => false]);

    $this->post('/demo/unlock')->assertNotFound();
});

test('the demo is on by default', function () {
    $this->get('/')
        ->assertSee('data-try-it-modal', false)
        ->assertSee('Try it! Live Demo');

    $this->get(route('demo.command-center'))->assertOk();
});
