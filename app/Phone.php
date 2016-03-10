<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    public $incrementing = false;
    public $fillable = ['id'];

    public function offers()
    {
        return $this->belongsToMany(Offer::class);
    }
}
