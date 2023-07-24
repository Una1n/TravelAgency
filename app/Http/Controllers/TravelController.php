<?php

namespace App\Http\Controllers;

use App\Http\Resources\TravelResource;
use App\Models\Travel;

class TravelController extends Controller
{
    public function index()
    {
        $travels = Travel::query()->public()->paginate(10);

        return TravelResource::collection($travels);
    }
}
