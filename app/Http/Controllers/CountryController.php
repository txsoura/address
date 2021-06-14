<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return CountryResource::collection(
            Country::when($request['include'], function ($query, $include) {
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
            'code' => 'required|string|unique:countries'
        ]);

        $country = Country::create($request->all());

        return new CountryResource($country, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Country $country)
    {
        return new CountryResource(
            Country::where('id', $country->id)
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
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        $request['name'] = ucwords($request['name']);
        $request['code'] = Str::upper($request['code']);

        $request->validate([
            'name' => 'string',
            'code' => 'string|unique:countries'
        ]);

        $country->update($request->all());

        return new CountryResource($country, 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        $country->delete();
        return response()->json(['message' => trans('message.deleted')], 204);
    }
}
