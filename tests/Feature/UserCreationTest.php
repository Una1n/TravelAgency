<?php

use App\Models\Role;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\post;

it('can create user as admin', function () {
    $user = User::factory()->create();
    $role = Role::factory()->create(['name' => 'admin']);
    $user->roles()->attach($role);
    Sanctum::actingAs($user, ['*']);

    $response = post(route('users.store'), [
        'name' => 'Henk Stubbe',
        'email' => 'henk@stubbe.nl',
        'password' => 'password',
    ]);

    $response->assertJsonPath('message', 'User created successfully.');
    $response->assertJsonPath('data.name', 'Henk Stubbe');
    $response->assertJsonPath('data.email', 'henk@stubbe.nl');
    $response->assertCreated();
});

it('cant access create user if not admin', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $response = post(route('users.store'), [
        'name' => 'Henk Stubbe',
        'email' => 'henk@stubbe.nl',
        'password' => 'password',
    ]);

    $response->assertForbidden();
});
