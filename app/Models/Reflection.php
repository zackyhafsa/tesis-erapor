<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reflection extends Model
{
    protected $guarded = [];

    protected $casts = [
        'kelebihan_siswa' => 'array',
        'aspek_ditingkatkan' => 'array',
        'tindak_lanjut' => 'array',
    ];

    public function schoolProfile(): BelongsTo
    {
        return $this->belongsTo(SchoolProfile::class);
    }
}
