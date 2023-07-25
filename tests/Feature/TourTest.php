<?php

use App\Models\Tour;
use App\Models\Travel;
use Carbon\Carbon;
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
        'price' => 500,
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

it('sorts the tours by starting date in a specific travel', function () {
    $travel = Travel::factory()->create();
    $lastTour = Tour::factory()->create([
        'travel_id' => $travel->id,
        'start_date' => now()->addDays(4),
        'end_date' => now()->addDays(10),
    ]);
    $firstTour = Tour::factory()->create([
        'travel_id' => $travel->id,
        'start_date' => now(),
        'end_date' => now()->addDay(),
    ]);

    $response = getJson(route('tours.index', $travel));
    $response->assertJsonCount(2, 'data');
    $response->assertJsonPath('data.0.id', $firstTour->id);
    $response->assertJsonPath('data.1.id', $lastTour->id);
    $response->assertOk();
});

it('sorts the tours by price in a specific travel', function () {
    $travel = Travel::factory()->create();
    $cheapTour = Tour::factory()->create([
        'travel_id' => $travel->id,
        'price' => 100,
    ]);
    $expensiveTour = Tour::factory()->create([
        'travel_id' => $travel->id,
        'price' => 200,
    ]);

    $response = getJson(route('tours.index', [
        $travel,
        'sortPrice' => 'asc',
    ]));
    $response->assertJsonCount(2, 'data');
    $response->assertJsonPath('data.0.id', $cheapTour->id);
    $response->assertJsonPath('data.1.id', $expensiveTour->id);
    $response->assertOk();
});

it('shows the tours by price From/To in a specific travel', function () {
    $travel = Travel::factory()->create();
    Tour::factory()->create([
        'travel_id' => $travel->id,
        'start_date' => now()->addDays(3),
        'end_date' => now()->addDays(9),
        'price' => 100,
    ]);
    $cheapTour = Tour::factory()->create([
        'travel_id' => $travel->id,
        'start_date' => now()->addDays(4),
        'end_date' => now()->addDays(10),
        'price' => 200,
    ]);
    $expensiveTour = Tour::factory()->create([
        'travel_id' => $travel->id,
        'start_date' => now()->addDays(5),
        'end_date' => now()->addDays(11),
        'price' => 400,
    ]);
    Tour::factory()->create([
        'travel_id' => $travel->id,
        'start_date' => now()->addDays(6),
        'end_date' => now()->addDays(12),
        'price' => 500,
    ]);

    $response = getJson(route('tours.index', [
        $travel,
        'priceFrom' => 200,
        'priceTo' => 400,
    ]));
    $response->assertJsonCount(2, 'data');
    $response->assertJsonPath('data.0.id', $cheapTour->id);
    $response->assertJsonPath('data.1.id', $expensiveTour->id);
    $response->assertOk();
});

it('shows the tours within start/end date in a specific travel', function () {
    $nowDate = Carbon::create(2023, 7, 15);
    Carbon::setTestNow($nowDate);

    $travel = Travel::factory()->create();
    Tour::factory()->create([
        'travel_id' => $travel->id,
        'start_date' => now(),
    ]);
    $earlyTour = Tour::factory()->create([
        'travel_id' => $travel->id,
        'start_date' => now()->addDays(4),
    ]);
    $lateTour = Tour::factory()->create([
        'travel_id' => $travel->id,
        'start_date' => now()->addDays(6),
    ]);
    Tour::factory()->create([
        'travel_id' => $travel->id,
        'start_date' => now()->addDays(7),
    ]);

    $response = getJson(route('tours.index', [
        $travel,
        'dateFrom' => now()->addDays(4)->format('Y-m-d'),
        'dateTo' => now()->addDays(6)->format('Y-m-d'),
    ]));
    $response->assertJsonCount(2, 'data');
    $response->assertJsonPath('data.0.id', $earlyTour->id);
    $response->assertJsonPath('data.1.id', $lateTour->id);
    $response->assertOk();
});
