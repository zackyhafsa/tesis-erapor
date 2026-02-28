<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scores(): HasMany
    {
        // Artinya: 1 Siswa memiliki Banyak (HasMany) Nilai
        return $this->hasMany(Score::class);
    }
}
