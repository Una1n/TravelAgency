<?php

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

    $travel = Travel::query()->whereId($response->json('data.id'))->first();
    expect($travel)->not->ToBeNull();
    expect($travel->number_of_nights)->toEqual(4);

    $response->assertJsonPath('message', 'Travel updated successfully.');
    $response->assertJsonPath('data.name', 'Japan: road to Wonder');
    $response->assertJsonPath('data.description', 'A nice tour of Japan');
    $response->assertJsonPath('data.number_of_days', 5);
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
