<?php

namespace App\Http\Controllers;

use App\Events\AddressCreated;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OwnerAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $ownerType, int $ownerId)
    {
        return AddressResource::collection(
            Address::when($request['include'], function ($query, $include) {
                return $query->with(explode(',',  $include));
            })
                ->where('owner_type',  $ownerType)
                ->where('owner_id',  $ownerId)
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
    public function store(Request $request, $ownerType, int $ownerId)
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
            'city_id' => 'required|numeric|exists:cities,id'
        ]);

        $request['name'] = ucwords($request['name']);
        $request['district'] = ucwords($request['district']);
        $request['street'] = ucwords($request['street']);
        $request['owner_type'] = Str::lower($ownerType);
        $request['owner_id'] = $ownerId;

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
    public function show(Request $request, $ownerType, int $ownerId, Address $address)
    {
        return new AddressResource(
            Address::where('id', $address->id)
                ->where('owner_type',  $ownerType)
                ->where('owner_id',  $ownerId)
                ->when($request['include'], function ($query, $include) {
                    return $query->with(explode(',',  $include));
                })->firstOrFail(),
            200
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ownerType, int $ownerId, Address $address)
    {
        if ($address->owner_id != $ownerId || $address->owner_type != $ownerType) {
            return response()->json([
                'message' => trans('message.not_found'), 'error' => trans('message.entry_not_found', ['model' => 'Address'])
            ], 404);
        }

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
    public function destroy($ownerType, int $ownerId, Address $address)
    {
        if ($address->owner_id != $ownerId || $address->owner_type != $ownerType) {
            return response()->json([
                'message' => trans('message.not_found'), 'error' => trans('message.entry_not_found', ['model' => 'Address'])
            ], 404);
        }

        $address->delete();
        return response()->json(['message' => trans('message.deleted')], 204);
    }
}
