<?php

use App\Models\Travel;

use function Pest\Laravel\getJson;

it('can show a list of travel', function () {
    Travel::factory(15)->create([
        'is_public' => 1,
    ]);

    $response = getJson(route('travel.index'));
    $response->assertJsonCount(10, 'data');
    $response->assertJsonPath('meta.last_page', 2);
    $response->assertOk();
});

it('can show only public travel', function () {
    Travel::factory(15)->create([
        'is_public' => 0,
    ]);
    $publicTravel = Travel::factory()->create([
        'is_public' => 1,
    ]);

    $response = getJson(route('travel.index'));
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.name', $publicTravel->name);
    $response->assertOk();
});

it('can generate unique slugs', function () {
    // Create 3x travel with the same name
    $travel = Travel::factory(3)->create([
        'name' => 'This is a test name',
    ]);

    // Slugs should be different!
    expect($travel->first()->slug)->toEqual('this-is-a-test-name');
    expect($travel->get(1)->slug)->toEqual('this-is-a-test-name-1');
    expect($travel->get(2)->slug)->toEqual('this-is-a-test-name-2');
});
