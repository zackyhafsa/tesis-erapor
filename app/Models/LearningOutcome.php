<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningOutcome extends Model
{
    protected $guarded = [];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
