<?php

use App\Models\Tour;
use App\Models\Travel;
use function Pest\Laravel\getJson;

it('returns the correct tours in a specific travel', function () {
    $travel = Travel::factory()
        ->has(Tour::factory())
        ->create();

    $response = getJson(route('tours.index', $travel));
    $response->assertJsonCount(1, 'data');
    $response->assertJsonFragment(['id' => $travel->tours()->first()->id]);
    $response->assertOk();
});

it('shows the correct price of a tour in a specific travel', function () {
    $travel = Travel::factory()->create();
    Tour::factory()->create([
        'travel_id' => $travel->id,
        'price_in_cents' => 50000,
    ]);

    $response = getJson(route('tours.index', $travel));
    $response->assertJsonCount(1, 'data');
    $response->assertJsonFragment(['price' => '500.00']);
    $response->assertOk();
});

it('can paginate the tours in a specific travel', function () {
    $travel = Travel::factory()
        ->has(Tour::factory(15))
        ->create();

    $response = getJson(route('tours.index', $travel));
    $response->assertJsonCount(10, 'data');
    $response->assertJsonPath('meta.last_page', 2);
    $response->assertOk();
});
