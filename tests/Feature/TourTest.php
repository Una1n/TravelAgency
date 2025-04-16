<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\Travel;
use Carbon\Carbon;

use function Pest\Laravel\getJson;

it('returns the correct tours in a specific travel', function () {
    $travel = Travel::factory()
        ->has(Tour::factory())
        ->create();

    $response = getJson(route('tours.index', $travel));
    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data')[0])->id->toBe($travel->tours()->first()->id);
    $response->assertOk();
});

it('shows the correct price of a tour in a specific travel', function () {
    $travel = Travel::factory()->create();
    Tour::factory()->create([
        'travel_id' => $travel->id,
        'price' => 500,
    ]);

    $response = getJson(route('tours.index', $travel));
    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data')[0])->price->toBe('500.00');
    $response->assertOk();
});

it('can paginate the tours in a specific travel', function () {
    $travel = Travel::factory()
        ->has(Tour::factory(15))
        ->create();

    $response = getJson(route('tours.index', $travel));
    expect($response->json('data'))->toHaveCount(10);
    expect($response->json('meta'))->last_page->toBe(2);
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
    expect($response->json('data'))->toHaveCount(2);
    expect($response->json('data')[0])->id->toBe($firstTour->id);
    expect($response->json('data')[1])->id->toBe($lastTour->id);
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
    expect($response->json('data'))->toHaveCount(2);
    expect($response->json('data')[0])->id->toBe($cheapTour->id);
    expect($response->json('data')[1])->id->toBe($expensiveTour->id);
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
    expect($response->json('data'))->toHaveCount(2);
    expect($response->json('data')[0])->id->toBe($cheapTour->id);
    expect($response->json('data')[1])->id->toBe($expensiveTour->id);
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
    expect($response->json('data'))->toHaveCount(2);
    expect($response->json('data')[0])->id->toBe($earlyTour->id);
    expect($response->json('data')[1])->id->toBe($lateTour->id);
    $response->assertOk();
});
