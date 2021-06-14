<?php

use Illuminate\Http\Request;
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

Route::group(['prefix' => 'v1'], function () {
    Route::apiResource('{ownerType}/{ownerId}/addresses', 'OwnerAddressController')->names('addresses');

    Route::apiResource('addresses', 'AddressController');
    Route::apiResource('cities', 'CityController');
    Route::apiResource('states', 'StateController');
    Route::apiResource('countries', 'CountryController');
});
