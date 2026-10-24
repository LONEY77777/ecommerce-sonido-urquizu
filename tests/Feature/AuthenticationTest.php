<?php

use App\Enums\UserRole;
use App\Models\User;

it('shows the registration form to guests', function () {
    $this->get(route('register'))->assertSuccessful();
});

it('registers a new customer and signs them in', function () {
    $response = $this->post(route('register'), [
        'name' => 'Nadia Rahman',
        'email' => 'nadia@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('home'));
    $this->assertAuthenticated();

    $user = User::where('email', 'nadia@example.com')->sole();

    expect($user->name)->toBe('Nadia Rahman')
        ->and($user->role)->toBe(UserRole::User);
});

it('never lets a registrant assign themselves the admin role', function () {
    $this->post(route('register'), [
        'name' => 'Sneaky User',
        'email' => 'sneaky@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'admin',
    ]);

    expect(User::where('email', 'sneaky@example.com')->sole()->role)->toBe(UserRole::User);
});

it('rejects registration with invalid data', function (array $payload, string $field) {
    $this->post(route('register'), $payload)->assertInvalid($field);

    $this->assertGuest();
})->with([
    'missing name' => [
        ['name' => '', 'email' => 'a@example.com', 'password' => 'password123', 'password_confirmation' => 'password123'],
        'name',
    ],
    'invalid email' => [
        ['name' => 'A', 'email' => 'not-an-email', 'password' => 'password123', 'password_confirmation' => 'password123'],
        'email',
    ],
    'mismatched confirmation' => [
        ['name' => 'A', 'email' => 'a@example.com', 'password' => 'password123', 'password_confirmation' => 'different'],
        'password',
    ],
    'password without numbers' => [
        ['name' => 'A', 'email' => 'a@example.com', 'password' => 'passwordonly', 'password_confirmation' => 'passwordonly'],
        'password',
    ],
]);

it('rejects registration with a duplicate email', function () {
    User::factory()->create(['email' => 'taken@example.com']);

    $this->post(route('register'), [
        'name' => 'Someone',
        'email' => 'taken@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertInvalid('email');
});

it('shows the login form to guests', function () {
    $this->get(route('login'))->assertSuccessful();
});

it('logs a customer in and sends them to the homepage', function () {
    $user = User::factory()->create(['email' => 'customer@example.com']);

    $this->post(route('login'), [
        'email' => 'customer@example.com',
        'password' => 'password',
    ])->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($user);
});

it('sends an admin to the homepage after login too', function () {
    $admin = User::factory()->admin()->create(['email' => 'admin@example.com']);

    $this->post(route('login'), [
        'email' => 'admin@example.com',
        'password' => 'password',
    ])->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($admin);
});

it('rejects a login with the wrong password', function () {
    User::factory()->create(['email' => 'customer@example.com']);

    $this->post(route('login'), [
        'email' => 'customer@example.com',
        'password' => 'wrong-password',
    ])->assertInvalid('email');

    $this->assertGuest();
});

it('logs the user out', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('logout'))
        ->assertRedirect(route('home'));

    $this->assertGuest();
});

it('keeps signed in users away from the login and register forms', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('login'))->assertRedirect();
    $this->get(route('register'))->assertRedirect();
});

it('shows a dashboard link in the navbar for admins only', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->get(route('home'))
        ->assertSee(route('admin.dashboard'));

    $this->actingAs(User::factory()->create())
        ->get(route('home'))
        ->assertDontSee(route('admin.dashboard'));
});
