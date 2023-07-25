<?php

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

    $travel = Travel::query()->whereId($response->json('data.id'))->first();
    expect($travel)->not->ToBeNull();
    expect($travel->number_of_nights)->toEqual(4);

    $response->assertJsonPath('message', 'Travel created successfully.');
    $response->assertJsonPath('data.name', 'Japan: road to Wonder');
    $response->assertJsonPath('data.description', 'A nice tour of Japan');
    $response->assertJsonPath('data.number_of_days', 5);
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
