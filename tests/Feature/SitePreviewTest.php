<?php

use App\Filament\Resources\PreviewPasswords\Pages\ManagePreviewPasswords;
use App\Http\Middleware\RequireSitePreviewPassword;
use App\Models\PreviewPassword;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(fn () => config(['app.preview.enabled' => true]));

test('the homepage asks for a password and hides its content', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('Enter the password to continue.')
        ->assertSee('<meta name="robots" content="noindex, nofollow">', false)
        ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
        ->assertDontSee('data-track-section="Hero"', false);
});

test('a client password unlocks the homepage and records its use', function () {
    $previewPassword = PreviewPassword::factory()->create(['password' => 'DCBuilders26']);

    $this->post(route('site-preview.store'), ['password' => 'DCBuilders26'])
        ->assertRedirect(route('home'))
        ->assertSessionHas(RequireSitePreviewPassword::SESSION_KEY, $previewPassword->id);

    $this->get('/')
        ->assertOk()
        ->assertSee('data-track-section="Hero"', false);

    expect($previewPassword->fresh())
        ->uses->toBe(1)
        ->last_used_at->not->toBeNull();
});

test('each client has their own password', function () {
    PreviewPassword::factory()->create(['password' => 'ClientOne11']);
    $second = PreviewPassword::factory()->create(['password' => 'ClientTwo22']);

    $this->post(route('site-preview.store'), ['password' => 'ClientTwo22'])
        ->assertSessionHas(RequireSitePreviewPassword::SESSION_KEY, $second->id);
});

test('a wrong, turned off or expired password keeps the homepage locked', function (string $typed) {
    PreviewPassword::factory()->inactive()->create(['password' => 'TurnedOff11']);
    PreviewPassword::factory()->expired()->create(['password' => 'Expired2222']);

    $this->from('/')
        ->post(route('site-preview.store'), ['password' => $typed])
        ->assertRedirect('/')
        ->assertSessionHasErrors('password')
        ->assertSessionMissing(RequireSitePreviewPassword::SESSION_KEY);
})->with(['wrong password' => 'Nope123', 'turned off' => 'TurnedOff11', 'expired' => 'Expired2222']);

test('turning a password off locks out a client who is already in', function () {
    $previewPassword = PreviewPassword::factory()->create(['password' => 'DCBuilders26']);
    $this->post(route('site-preview.store'), ['password' => 'DCBuilders26']);
    $this->get('/')->assertSee('data-track-section="Hero"', false);

    $previewPassword->update(['is_active' => false]);

    $this->get('/')
        ->assertSee('Enter the password to continue.')
        ->assertDontSee('data-track-section="Hero"', false);
});

test('deleting a password locks out a client who is already in', function () {
    $previewPassword = PreviewPassword::factory()->create(['password' => 'DCBuilders26']);
    $this->post(route('site-preview.store'), ['password' => 'DCBuilders26']);

    $previewPassword->delete();

    $this->get('/')->assertSee('Enter the password to continue.');
});

test('password attempts are rate limited', function () {
    PreviewPassword::factory()->create(['password' => 'DCBuilders26']);

    foreach (range(1, 5) as $attempt) {
        $this->post(route('site-preview.store'), ['password' => 'wrong']);
    }

    $this->post(route('site-preview.store'), ['password' => 'DCBuilders26'])->assertTooManyRequests();
});

test('the homepage is public when the gate is turned off', function () {
    config(['app.preview.enabled' => false]);
    PreviewPassword::factory()->create(['password' => 'DCBuilders26']);

    $this->get('/')
        ->assertOk()
        ->assertSee('data-track-section="Hero"', false);
});

test('passwords are encrypted in the database', function () {
    $previewPassword = PreviewPassword::factory()->create(['password' => 'DCBuilders26']);

    expect($previewPassword->getRawOriginal('password'))->not->toContain('DCBuilders26');
});

test('admins can create a client password', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(ManagePreviewPasswords::class)
        ->callAction('create', ['label' => 'DC Builders', 'password' => 'DCBuilders26', 'is_active' => true])
        ->assertHasNoFormErrors();

    expect(PreviewPassword::findUsableMatching('DCBuilders26'))->label->toBe('DC Builders');
});

test('two clients cannot share the same password', function () {
    $this->actingAs(User::factory()->create());
    PreviewPassword::factory()->create(['password' => 'DCBuilders26']);

    Livewire::test(ManagePreviewPasswords::class)
        ->callAction('create', ['label' => 'Another client', 'password' => 'DCBuilders26', 'is_active' => true])
        ->assertHasFormErrors(['password']);
});

test('admins can turn a client password off from the list', function () {
    $this->actingAs(User::factory()->create());
    $previewPassword = PreviewPassword::factory()->create();

    Livewire::test(ManagePreviewPasswords::class)
        ->assertCanSeeTableRecords([$previewPassword])
        ->callAction(TestAction::make('edit')->table($previewPassword), ['label' => $previewPassword->label, 'password' => $previewPassword->password, 'is_active' => false])
        ->assertHasNoFormErrors();

    expect($previewPassword->fresh()->is_active)->toBeFalse();
});
