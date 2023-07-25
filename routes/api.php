<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\UserController;
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

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

/**
 * Public Routes
 */
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::get('travels', [TravelController::class, 'index'])->name('travel.index');
Route::get('tours/{travel}', [TourController::class, 'index'])->name('tours.index');
