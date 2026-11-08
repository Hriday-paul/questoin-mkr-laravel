<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

test('registration screen can be rendered without role selection', function () {
    $response = $this->get(route('register'));

    $response->assertOk()
        ->assertSee('Organization Name')
        ->assertSee('Phone Number')
        ->assertDontSee('name="role"', escape: false);
});

test('new users can register as user role and receive email verification', function () {
    Notification::fake();

    $response = $this->post(route('register.store'), [
        'organization_name' => 'Acme Institute',
        'email' => 'contact@acme.test',
        'phone_number' => '+15551234567',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'role' => 'admin',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('verification.notice'));

    $user = User::where('email', 'contact@acme.test')->firstOrFail();

    expect($user->organization_name)->toBe('Acme Institute')
        ->and($user->phone_number)->toBe('+15551234567')
        ->and($user->role)->toBe(UserRole::User)
        ->and($user->hasVerifiedEmail())->toBeFalse();

    Notification::assertSentTo($user, VerifyEmail::class);
});

test('unverified user is redirected to email verification notice', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('verification.notice'));
});

test('email can be verified via signed link', function () {
    Event::fake();

    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    Event::assertDispatched(Verified::class);
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertRedirect(route('dashboard', absolute: false).'?verified=1');
});

test('regular user can login via user login route', function () {
    $user = User::factory()->create([
        'email' => 'user@organization.test',
        'password' => 'secret-password',
        'role' => UserRole::User,
    ]);

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'secret-password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('admin cannot login via regular user login route', function () {
    $admin = User::factory()->admin()->create([
        'email' => 'admin@organization.test',
        'password' => 'secret-password',
    ]);

    $response = $this->from(route('login'))->post(route('login.store'), [
        'email' => $admin->email,
        'password' => 'secret-password',
    ]);

    $this->assertGuest();
    $response->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');
});

test('admin can login via dedicated admin login route', function () {
    $admin = User::factory()->admin()->create([
        'email' => 'admin@organization.test',
        'password' => 'secret-password',
    ]);

    $response = $this->post(route('admin.login.store'), [
        'email' => $admin->email,
        'password' => 'secret-password',
    ]);

    $this->assertAuthenticatedAs($admin);
    $response->assertRedirect(route('admin.dashboard', absolute: false));
});

test('regular user cannot login via admin login route', function () {
    $user = User::factory()->create([
        'email' => 'user@organization.test',
        'password' => 'secret-password',
        'role' => UserRole::User,
    ]);

    $response = $this->from(route('admin.login'))->post(route('admin.login.store'), [
        'email' => $user->email,
        'password' => 'secret-password',
    ]);

    $this->assertGuest();
    $response->assertRedirect(route('admin.login'))
        ->assertSessionHasErrors('email');
});

test('regular user cannot access admin dashboard and admin cannot access user dashboard', function () {
    $user = User::factory()->create(['role' => UserRole::User]);
    $admin = User::factory()->admin()->create();

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertForbidden();
});
