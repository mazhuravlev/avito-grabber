<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrabbedLink extends Model
{
    public $fillable = ['href'];

    public function offer()
    {
        return $this->hasOne(Offer::class);
    }
}
