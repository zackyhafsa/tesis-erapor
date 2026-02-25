<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    protected $guarded = [];

    public function aspect()
    {
        return $this->belongsTo(Aspect::class);
    }
}
