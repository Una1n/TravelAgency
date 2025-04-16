<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Travel;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\postJson;

it('can create travel as admin', function () {
    $user = User::factory()->create();
    $role = Role::factory()->create(['name' => 'admin']);
    $user->roles()->attach($role);
    Sanctum::actingAs($user, ['*']);

    $response = postJson(route('travel.store'), [
        'name' => 'Japan: road to Wonder',
        'description' => 'A nice tour of Japan',
        'number_of_days' => 5,
        'is_public' => false,
    ]);

    $travel = Travel::whereId($response->json('data.id'))->first();
    expect($travel)->not->toBeNull();
    expect($travel->number_of_nights)->toEqual(4);

    expect($response->json('message'))->toBe('Travel created successfully.');
    expect($response->json('data'))->name->toBe('Japan: road to Wonder');
    expect($response->json('data'))->description->toBe('A nice tour of Japan');
    expect($response->json('data'))->number_of_days->toBe(5);
    $response->assertCreated();
});

it('cant access create travel if not admin', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $response = postJson(route('travel.store'), [
        'name' => 'Japan: road to Wonder',
        'description' => 'A nice tour of Japan',
        'number_of_days' => 5,
        'is_public' => false,
    ]);

    $response->assertForbidden();
});
