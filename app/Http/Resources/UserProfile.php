<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfile extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user_info = parent::toArray($request);
        $user_info['profile_image'] = url('profile_image/'.$user_info['profile_image']);
        return $user_info;
    }
}
