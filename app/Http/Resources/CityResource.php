<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'state_id' => $this->state_id,
            'state' => new StateResource($this->whenLoaded('state')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
