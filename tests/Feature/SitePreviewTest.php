<?php

use App\Http\Middleware\RequireSitePreviewPassword;
use Illuminate\Support\Facades\Hash;

beforeEach(fn () => config(['app.preview_password_hash' => Hash::make('L@nding2027')]));

test('the homepage asks for a password and hides its content', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('Enter the password to continue.')
        ->assertSee('<meta name="robots" content="noindex, nofollow">', false)
        ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
        ->assertDontSee('data-track-section="Hero"', false);
});

test('the right password unlocks the homepage for the session', function () {
    $this->post(route('site-preview.store'), ['password' => 'L@nding2027'])
        ->assertRedirect(route('home'))
        ->assertSessionHas(RequireSitePreviewPassword::SESSION_KEY, true);

    $this->get('/')
        ->assertOk()
        ->assertSee('data-track-section="Hero"', false)
        ->assertDontSee('Enter the password to continue.');
});

test('a wrong password keeps the homepage locked', function () {
    $this->from('/')
        ->post(route('site-preview.store'), ['password' => 'landing2027'])
        ->assertRedirect('/')
        ->assertSessionHasErrors('password')
        ->assertSessionMissing(RequireSitePreviewPassword::SESSION_KEY);
});

test('password attempts are rate limited', function () {
    foreach (range(1, 5) as $attempt) {
        $this->post(route('site-preview.store'), ['password' => 'wrong']);
    }

    $this->post(route('site-preview.store'), ['password' => 'L@nding2027'])->assertTooManyRequests();
});

test('the homepage is public when no preview password is set', function () {
    config(['app.preview_password_hash' => '']);

    $this->get('/')
        ->assertOk()
        ->assertSee('data-track-section="Hero"', false);
});

test('the committed default hash matches the preview password', function () {
    expect(password_verify('L@nding2027', '$2y$12$pQBmLK/9gU9nW7qYtCdYJ.NbyVvWKNOXFHERrLcgx3C3MGMCQvff6'))->toBeTrue()
        ->and(file_get_contents(config_path('app.php')))->toContain('$2y$12$pQBmLK/9gU9nW7qYtCdYJ.NbyVvWKNOXFHERrLcgx3C3MGMCQvff6');
});
