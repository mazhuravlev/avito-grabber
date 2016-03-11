<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{

    public $timestamps = false;
    public $fillable = [
        'href', 'offer_id'
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

}
