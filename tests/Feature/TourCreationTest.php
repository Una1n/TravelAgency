<?php

use App\Models\Role;
use App\Models\Travel;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\postJson;

it('can create tours as admin', function () {
    $user = User::factory()->create();
    $role = Role::factory()->create(['name' => 'admin']);
    $user->roles()->attach($role);
    Sanctum::actingAs($user, ['*']);

    $travel = Travel::factory()->create();

    $response = postJson(route('tours.store', $travel), [
        'name' => 'Tour Name',
        'start_date' => now()->format('Y-m-d'),
        'end_date' => now()->addDays(5)->format('Y-m-d'),
        'price' => 499.99,
    ]);

    $travel->refresh();

    expect($travel->tours)->not->ToBeNull();
    expect($travel->tours)->toHaveCount(1);

    $response->assertJsonPath('message', 'Tour created successfully.');
    $response->assertJsonPath('data.name', 'Tour Name');
    $response->assertJsonPath('data.price', '499.99');
    $response->assertCreated();
});

it('cant access create tours if not admin', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $travel = Travel::factory()->create();

    $response = postJson(route('tours.store', $travel), [
        'name' => 'Tour Name',
        'start_date' => now(),
        'end_date' => now()->addDays(5),
        'price' => 499.99,
    ]);

    $response->assertForbidden();
});
