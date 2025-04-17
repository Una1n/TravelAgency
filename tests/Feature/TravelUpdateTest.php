<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Travel;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\patchJson;

it('can update travel as editor', function () {
    $user = User::factory()->create();
    $role = Role::factory()->create(['name' => 'editor']);
    $user->roles()->attach($role);
    Sanctum::actingAs($user, ['*']);

    $travel = Travel::factory()->create();

    $response = patchJson(route('travel.update', $travel), [
        'name' => 'Japan: road to Wonder',
        'description' => 'A nice tour of Japan',
        'number_of_days' => 5,
    ]);

    $travel = Travel::whereId($response->json('data.id'))->first();
    expect($travel)->not->toBeNull();
    expect($travel->number_of_nights)->toEqual(4);

    expect($response->json('message'))->toBe('Travel updated successfully.');
    expect($response->json('data'))->name->toBe('Japan: road to Wonder');
    expect($response->json('data'))->description->toBe('A nice tour of Japan');
    expect($response->json('data'))->number_of_days->toBe(5);
    $response->assertOk();
});

it('cant access update travel if not editor', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $travel = Travel::factory()->create();

    $response = patchJson(route('travel.update', $travel), [
        'name' => 'Japan: road to Wonder',
        'description' => 'A nice tour of Japan',
        'number_of_days' => 5,
        'is_public' => false,
    ]);

    $response->assertForbidden();
});
