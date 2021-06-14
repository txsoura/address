<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return CityResource::collection(
            City::when($request['include'], function ($query, $include) {
                return $query->with(explode(',',  $include));
            })
                ->get(),
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['name'] = ucwords($request['name']);
        $request['code'] = Str::upper($request['code']);

        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:cities',
            'state_id' =>  'required|numeric|exists:states,id',
        ]);

        $city = City::create($request->all());
        return new CityResource($city, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, City $city)
    {
        return new CityResource(
            City::where('id', $city->id)
                ->when($request['include'], function ($query, $include) {
                    return $query->with(explode(',',  $include));
                })
                ->firstOrFail(),
            200
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        $request['name'] = ucwords($request['name']);
        $request['code'] = Str::upper($request['code']);

        $request->validate([
            'name' => 'string',
            'code' => 'string|unique:cities',
        ]);

        $city->update($request->all());

        return new CityResource($city, 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        $city->delete();
        return response()->json(['message' => trans('message.deleted')], 204);
    }
}
