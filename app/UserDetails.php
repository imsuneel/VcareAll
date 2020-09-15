<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    protected $guarded = [];
    public function city(){
        return $this->belongsTo(\App\City::class);
    }

    public function country(){
        return $this->belongsTo(\App\Country::class);
    }

    

    public function state(){
        return $this->belongsTo(\App\State::class);
    }
    
}
