<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aspect extends Model
{
    protected $guarded = [];

    public function indicators(): HasMany
    {
        // Artinya: 1 Aspek memiliki Banyak (HasMany) Indikator
        return $this->hasMany(Indicator::class);
    }
}
