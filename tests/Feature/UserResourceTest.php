<?php

use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create();
    $this->actingAs($this->admin);
});

test('admins can see every user', function () {
    $users = User::factory()->count(3)->create();

    Livewire::test(ManageUsers::class)
        ->assertCanSeeTableRecords($users->push($this->admin));
});

test('admins can add a user with a password', function () {
    Livewire::test(ManageUsers::class)
        ->callAction('create', ['name' => 'Ana Reyes', 'email' => 'ana@ziontek.co', 'password' => 'Str0ng-Passw0rd'])
        ->assertHasNoFormErrors();

    $user = User::firstWhere('email', 'ana@ziontek.co');

    expect($user->name)->toBe('Ana Reyes')
        ->and(Hash::check('Str0ng-Passw0rd', $user->password))->toBeTrue();
});

test('a new user needs a password and a unique email', function () {
    Livewire::test(ManageUsers::class)
        ->callAction('create', ['name' => 'Copy', 'email' => $this->admin->email, 'password' => ''])
        ->assertHasFormErrors(['email' => 'unique', 'password' => 'required']);
});

test('editing a user without a new password keeps the old one', function () {
    $user = User::factory()->create(['password' => 'Original-Passw0rd']);

    Livewire::test(ManageUsers::class)
        ->callAction(TestAction::make('edit')->table($user), ['name' => 'Renamed', 'email' => $user->email, 'password' => ''])
        ->assertHasNoFormErrors();

    expect($user->fresh())
        ->name->toBe('Renamed')
        ->and(Hash::check('Original-Passw0rd', $user->fresh()->password))->toBeTrue();
});

test('admins can change a user password', function () {
    $user = User::factory()->create();

    Livewire::test(ManageUsers::class)
        ->callAction(TestAction::make('edit')->table($user), ['name' => $user->name, 'email' => $user->email, 'password' => 'Brand-New-Passw0rd'])
        ->assertHasNoFormErrors();

    expect(Hash::check('Brand-New-Passw0rd', $user->fresh()->password))->toBeTrue();
});

test('admins can delete other users but not themselves', function () {
    $user = User::factory()->create();

    Livewire::test(ManageUsers::class)
        ->assertActionHidden(TestAction::make('delete')->table($this->admin))
        ->callAction(TestAction::make('delete')->table($user));

    expect(User::find($user->id))->toBeNull();
});
