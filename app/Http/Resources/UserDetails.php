<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDetails extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user_details = parent::toArray($request);
        $user_details['city_name'] = $this->city->name;
        $user_details['country_name'] = $this->country->name;
        $user_details['state_name'] = $this->state->name;
        return $user_details;
    }
}
