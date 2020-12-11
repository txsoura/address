<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request['include']) {
            return CityResource::collection(City::with(explode(',', $request['include']))->get(), 200);
        } else {
            return CityResource::collection(City::all(), 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        if ($request['include']) {
            return CityResource::collection(City::where('id', $city->id)->with(explode(',', $request['include']))->get(), 200);
        } else {
            return new CityResource($city, 200);
        }
        return new CityResource($city, 200);
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
