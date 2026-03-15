<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenilaianConfig extends Model
{
    protected $guarded = [];

    protected $casts = [
        'aspect_ids' => 'array',
        'cp_ids' => 'array',
        'tp_ids' => 'array',
        'presets' => 'array',
    ];

    public function schoolProfile(): BelongsTo
    {
        return $this->belongsTo(SchoolProfile::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
