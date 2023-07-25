<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTourRequest;
use App\Http\Requests\TourListRequest;
use App\Http\Resources\TourResource;
use App\Models\Travel;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TourController extends Controller
{
    public function index(Travel $travel, TourListRequest $request): JsonResource
    {
        $tours = $travel->tours()
            ->when($request->query('priceFrom'),
                fn ($query, string $priceFrom) => $query->priceFrom($priceFrom))
            ->when($request->query('priceTo'),
                fn ($query, string $priceTo) => $query->priceTo($priceTo))
            ->when($request->query('dateFrom'),
                fn ($query) => $query->dateFrom(Carbon::createFromFormat('Y-m-d', $request->query('dateFrom'))))
            ->when($request->query('dateTo'),
                fn ($query) => $query->dateTo(Carbon::createFromFormat('Y-m-d', $request->query('dateTo'))))
            ->when($request->query('sortPrice'),
                fn ($query, string $order) => $query->orderBy('price', $order));

        return TourResource::collection(
            $tours->orderBy('start_date')->paginate(10)
        );
    }

    public function store(Travel $travel, StoreTourRequest $request): JsonResource
    {
        $tour = $travel->tours()->create($request->validated());

        return TourResource::make($tour)->additional([
            'message' => 'Tour created successfully.',
        ]);
    }
}
