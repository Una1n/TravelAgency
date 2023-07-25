<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\postJson;

it('can login as user', function () {
    $user = User::factory()->create();

    $response = postJson(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertJsonPath('data.id', $user->id);
    $response->assertJsonPath('data.name', $user->name);
    $response->assertJsonPath('data.email', $user->email);
    $response->assertJsonPath('authorization.type', 'bearer');
    $response->assertOk();
});

it('cant login with invalid credentials', function () {
    $user = User::factory()->create();

    $response = postJson(route('login'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertJsonPath('message', 'Invalid credentials.');
    $response->assertUnauthorized();
});

it('can logout as user', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $response = postJson(route('logout'));
    $response->assertJsonPath('message', 'Successfully logged out.');
    $response->assertOk();
});
