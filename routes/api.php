<?php

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

// Public routes
Route::group(['prefix' => 'v1'], function () {
    Route::apiResource('cities', 'CityController');
    Route::apiResource('states', 'StateController');
    Route::apiResource('countries', 'CountryController');
});

// Private routes
Route::group(['prefix' => 'v1', 'middleware' => ['jwt.auth']], function () {
    Route::apiResource('addresses', 'AddressController');
    Route::apiResource('cities', 'CityController', [
        'except' => [
            'index',
            'show'
        ]
    ])->middleware('jwt.auth');
    Route::apiResource('states', 'StateController', [
        'except' => [
            'index',
            'show'
        ]
    ])->middleware('jwt.auth');
    Route::apiResource('countries', 'CountryController', [
        'except' => [
            'index',
            'show'
        ]
    ])->middleware('jwt.auth');
});
