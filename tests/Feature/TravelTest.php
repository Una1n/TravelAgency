<?php

use App\Models\Travel;

use function Pest\Laravel\getJson;

it('can show a list of travel', function () {
    Travel::factory(15)->create([
        'is_public' => 1,
    ]);

    $response = getJson(route('travel.index'));
    expect($response->json('data'))->toHaveCount(10);
    expect($response->json('meta'))->last_page->toBe(2);
    expect($response)->assertOk();
});

it('can show only public travel', function () {
    Travel::factory(15)->create([
        'is_public' => 0,
    ]);
    $publicTravel = Travel::factory()->create([
        'is_public' => 1,
    ]);

    $response = getJson(route('travel.index'));
    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data')[0])->name->toBe($publicTravel->name);
    expect($response)->assertOk();
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
