<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\post;

it('can login as user', function () {
    $user = User::factory()->create();

    $response = post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertJsonPath('user.id', $user->id);
    $response->assertJsonPath('authorization.type', 'bearer');
    $response->assertOk();
});

it('cant login with invalid credentials', function () {
    $user = User::factory()->create();

    $response = post(route('login'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertJsonPath('message', 'Invalid credentials.');
    $response->assertUnauthorized();
});

it('can logout as user', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $response = post(route('logout'));
    $response->assertJsonPath('message', 'Successfully logged out.');
    $response->assertOk();
});
