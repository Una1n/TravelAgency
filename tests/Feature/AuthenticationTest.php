<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\postJson;

it('can login as user', function () {
    $user = User::factory()->create();

    $response = postJson(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    expect($response->json('data'))->id->toBe($user->id);
    expect($response->json('data'))->name->toBe($user->name);
    expect($response->json('data'))->email->toBe($user->email);
    expect($response->json('authorization'))->type->toBe('bearer');
    $response->assertOk();
});

it('cant login with invalid credentials', function () {
    $user = User::factory()->create();

    $response = postJson(route('login'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    expect($response->json('message'))->toBe('Invalid credentials.');
    $response->assertUnauthorized();
});

it('can logout as user', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $response = postJson(route('logout'));
    expect($response->json('message'))->toBe('Successfully logged out.');
    $response->assertOk();
});
