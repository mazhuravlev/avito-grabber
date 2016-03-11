<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    public $incrementing = false;

    public function grabbedLink()
    {
        return $this->belongsTo(GrabbedLink::class);
    }

    public function phones()
    {
        return $this->belongsToMany(Phone::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

}
