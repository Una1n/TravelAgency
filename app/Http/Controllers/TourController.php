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
            ->when(
                $request->validated('priceFrom'),
                fn ($query, string $priceFrom) => $query->priceFrom($priceFrom)
            )
            ->when(
                $request->validated('priceTo'),
                fn ($query, string $priceTo) => $query->priceTo($priceTo)
            )
            ->when(
                $request->validated('dateFrom'),
                fn ($query) => $query->dateFrom(
                    Carbon::createFromFormat('Y-m-d', $request->validated('dateFrom'))
                )
            )
            ->when(
                $request->validated('dateTo'),
                fn ($query) => $query->dateTo(
                    Carbon::createFromFormat('Y-m-d', $request->validated('dateTo'))
                )
            )
            ->when(
                $request->validated('sortPrice'),
                fn ($query, string $order) => $query->orderBy('price', $order)
            );

        return $tours->orderBy('start_date')->paginate(10)->toResourceCollection();
    }

    public function store(Travel $travel, StoreTourRequest $request): JsonResource
    {
        $tour = $travel->tours()->create($request->validated());

        return TourResource::make($tour)->additional([
            'message' => 'Tour created successfully.',
        ]);
    }
}
