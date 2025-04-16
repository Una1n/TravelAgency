<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\postJson;

it('can create a user as admin', function () {
    $user = User::factory()->create();
    $role = Role::factory()->create(['name' => 'admin']);
    $user->roles()->attach($role);
    Sanctum::actingAs($user, ['*']);

    $response = postJson(route('users.store'), [
        'name' => 'Henk Stubbe',
        'email' => 'henk@stubbe.nl',
        'password' => 'password',
    ]);

    expect($response->json('message'))->toBe('User created successfully.');
    expect($response->json('data'))->name->toBe('Henk Stubbe');
    expect($response->json('data'))->email->toBe('henk@stubbe.nl');
    $response->assertCreated();
});

it('can create a user with a role of editor', function () {
    $user = User::factory()->create();
    $role = Role::factory()->create(['name' => 'admin']);
    $user->roles()->attach($role);
    Sanctum::actingAs($user, ['*']);

    $response = postJson(route('users.store'), [
        'name' => 'Henk Stubbe',
        'email' => 'henk@stubbe.nl',
        'password' => 'password',
        'role' => 'editor',
    ]);

    expect($response->json('message'))->toBe('User created successfully.');
    expect($response->json('data'))->name->toBe('Henk Stubbe');
    expect($response->json('data'))->email->toBe('henk@stubbe.nl');
    expect($response->json('data')['role'][0])->name->toBe('editor');
    $response->assertCreated();
});

it('cant access create user if not admin', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $response = postJson(route('users.store'), [
        'name' => 'Henk Stubbe',
        'email' => 'henk@stubbe.nl',
        'password' => 'password',
    ]);

    $response->assertForbidden();
});
