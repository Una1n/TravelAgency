<?php

namespace App\Http\Controllers;

use App\Http\Resources\TourResource;
use App\Models\Travel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index(Request $request, Travel $travel)
    {
        $tours = $travel->tours();

        if ($request->has('priceFrom')) {
            $tours = $tours->priceFrom($request->get('priceFrom'));
        }

        if ($request->has('priceTo')) {
            $tours = $tours->priceTo($request->get('priceTo'));
        }

        if ($request->has('dateFrom')) {
            $tours = $tours->dateFrom(Carbon::create($request->get('dateFrom')));
        }

        if ($request->has('dateTo')) {
            $tours = $tours->dateTo(Carbon::create($request->get('dateTo')));
        }

        if ($request->has('price')) {
            $tours = $tours->orderBy('price_in_cents', $request->get('price'));
        }

        return TourResource::collection(
            $tours->orderBy('start_date')->paginate(10)
        );
    }
}
