<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Filament\Models\Contracts\HasName;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolProfile extends Model implements HasName, HasAvatar
{
    
    protected $guarded = [];

    public function getFilamentName(): string
    {
        return $this->nama_sekolah ?? 'Sekolah';
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->logo_kanan ? asset('storage/' . $this->logo_kanan) : null;
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function aspects(): HasMany
    {
        return $this->hasMany(Aspect::class);
    }

    public function indicators(): HasMany
    {
        return $this->hasMany(Indicator::class);
    }

    public function learningOutcomes(): HasMany
    {
        return $this->hasMany(LearningOutcome::class);
    }

    public function learningObjectives(): HasMany
    {
        return $this->hasMany(LearningObjective::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    public function reflections(): HasMany
    {
        return $this->hasMany(Reflection::class);
    }
}
