<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'street' => ucwords($this->street),
            'postcode' => $this->postcode,
            'number' => $this->number,
            'complement' => $this->complement,
            'district' => ucwords($this->district),
            'name' => ucwords($this->name),
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'user_id' =>  $this->user_id,
            'city_id' =>  $this->city_id,
            'city' => new CityResource($this->whenLoaded('city')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at
        ];
    }
}
