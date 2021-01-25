<?php

namespace App\Http\Controllers;

use App\Http\Resources\StateResource;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return StateResource::collection(
            State::when($request['include'], function ($query, $include) {
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
            'code' => 'required|string|unique:states',
            'country_id' =>  'required|numeric|exists:countries,id',
        ]);

        $state = State::create($request->all());
        return new StateResource($state, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, State $state)
    {
        return StateResource::collection(
            State::find($state->id)
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
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, State $state)
    {
        $request['name'] = ucwords($request['name']);
        $request['code'] = Str::upper($request['code']);

        $request->validate([
            'name' => 'string',
            'code' => 'string|unique:states',
        ]);

        $state->update($request->all());

        return new StateResource($state, 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function destroy(State $state)
    {
        $state->delete();
        return response()->json(['message' => trans('message.deleted')], 204);
    }
}
