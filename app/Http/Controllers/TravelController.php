<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTravelRequest;
use App\Http\Resources\TravelResource;
use App\Models\Travel;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelController extends Controller
{
    public function index(): JsonResource
    {
        $travels = Travel::query()->public()->paginate(10);

        return TravelResource::collection($travels);
    }

    public function store(StoreTravelRequest $request): JsonResource
    {
        $travel = Travel::create($request->validated());

        return TravelResource::make($travel)->additional([
            'message' => 'Travel created successfully.',
        ]);
    }

    public function update(StoreTravelRequest $request, Travel $travel): JsonResource
    {
        $travel->update($request->validated());

        return TravelResource::make($travel)->additional([
            'message' => 'Travel updated successfully.',
        ]);
    }
}
