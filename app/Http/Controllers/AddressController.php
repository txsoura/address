<?php

namespace App\Http\Controllers;

use App\Events\AddressCreated;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request['include']) {
            return AddressResource::collection(Address::with(explode(',', $request['include']))->get(), 200);
        } else {
            return AddressResource::collection(Address::all(), 200);
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
            'street' => 'required|string',
            'postcode' => 'numeric',
            'number' => 'required|numeric',
            'complement' => 'nullable|string',
            'district' => 'required|string',
            'name' => 'string',
            'longitude' => 'nullable|string',
            'latitude' => 'nullable|string',
            'user_id' =>  'nullable|numeric',
            'city_id' => 'required|numeric|exists:cities,id'
        ]);

        $request['name'] = ucwords($request['name']);
        $request['district'] = ucwords($request['district']);
        $request['street'] = ucwords($request['street']);

        $address = Address::create($request->all());

        if (!$request['latitude'] || !$request['longitude']) {
            event(new AddressCreated($address));
        }

        return new AddressResource($address, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Address $address)
    {
        if ($request['include']) {
            return AddressResource::collection(Address::where('id', $address->id)->with(explode(',', $request['include']))->get(), 200);
        } else {
            return new AddressResource($address, 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Address $address)
    {
        $request->validate([
            'street' => 'string',
            'postcode' => 'numeric',
            'number' => 'numeric',
            'complement' => 'nullable|string',
            'district' => 'string',
            'name' => 'string',
            'longitude' => 'string',
            'latitude' => 'string'
        ]);

        $request['name'] = ucwords($request['name']);
        $request['district'] = ucwords($request['district']);
        $request['street'] = ucwords($request['street']);

        $address->update($request->all());

        return new AddressResource($address, 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(Address $address)
    {
        $address->delete();
        return response()->json(['message' => trans('message.deleted')], 204);
    }
}
