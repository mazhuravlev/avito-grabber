<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{

    public $timestamps = false;
    public $fillable = [
        'ip', 'login', 'password', 'hits', 'fails'
    ];

    public function scopeBest(Builder $query)
    {
        return $query
            ->orderBy('fails', 'asc')
            ->orderBy('hits', 'asc');
    }

    public function hit()
    {
        $this->increment('hits');
    }

    public function fail()
    {
        $this->increment('fails');
    }

}
