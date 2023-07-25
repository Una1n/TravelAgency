<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\UserController;
use App\Models\Tour;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * Authenticated Routes
 */
Route::middleware('auth:sanctum')->group(function () {
    Route::post('admin/users/create', [UserController::class, 'store'])
        ->name('users.store')
        ->can('create', User::class);

    Route::controller(TravelController::class)->group(function () {
        Route::post('admin/travels/create', 'store')
            ->name('travel.store')
            ->can('create', Travel::class);

        Route::patch('editor/travels/{travel}/update', 'update')
            ->name('travel.update')
            ->can('update', 'travel');
    });

    Route::post('admin/travels/{travel}/tours/create', [TourController::class, 'store'])
        ->name('tours.store')
        ->can('create', Tour::class);

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

/**
 * Public Routes
 */
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::get('travels', [TravelController::class, 'index'])->name('travel.index');
Route::get('tours/{travel}', [TourController::class, 'index'])->name('tours.index');
