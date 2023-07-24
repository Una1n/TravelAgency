<?php

namespace App\Http\Controllers;

use App\Http\Requests\TourListRequest;
use App\Http\Resources\TourResource;
use App\Models\Travel;

class TourController extends Controller
{
    public function index(Travel $travel, TourListRequest $request)
    {
        $tours = $travel->tours()
            ->when($request->query('priceFrom'),
                fn ($query, string $priceFrom) => $query->priceFrom($priceFrom))
            ->when($request->query('priceTo'),
                fn ($query, string $priceTo) => $query->priceTo($priceTo))
            ->when($request->query('dateFrom'),
                fn ($query, string $dateFrom) => $query->dateFrom($dateFrom))
            ->when($request->query('dateTo'),
                fn ($query, string $dateTo) => $query->dateTo($dateTo))
            ->when($request->query('sortPrice'),
                fn ($query, string $order) => $query->orderBy('price_in_cents', $order));

        return TourResource::collection(
            $tours->orderBy('start_date')->paginate(10)
        );
    }
}
