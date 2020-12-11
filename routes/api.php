<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\StateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

# Version
Route::get('/v1', function () {
    return [
        'name' => config('app.name'),
        'version' => config('app.version'),
        'locale' => app()->getLocale(),
    ];
});

Route::fallback(function () {
    return response()->json(['message' => trans('message.not_found'), 'error' => trans('message.route_not_found')], 404);
});

// Public routes
Route::group(['prefix' => 'v1'], function () {
    Route::apiResource('cities', CityController::class);
    Route::apiResource('states', StateController::class);
    Route::apiResource('countries', CountryController::class);
});

// Private routes
Route::group(['prefix' => 'v1', 'middleware' => ['jwt.auth']], function () {
    Route::apiResource('addresses', AddressController::class);
    Route::apiResource('cities', CityController::class, [
        'except' => [
            'index',
            'show'
        ]
    ])->middleware('jwt.auth');
    Route::apiResource('states', StateController::class, [
        'except' => [
            'index',
            'show'
        ]
    ])->middleware('jwt.auth');
    Route::apiResource('countries', CountryController::class, [
        'except' => [
            'index',
            'show'
        ]
    ])->middleware('jwt.auth');
});
