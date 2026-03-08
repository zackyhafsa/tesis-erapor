<?php

namespace App\Filament\Imports;

use App\Models\Aspect;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class AspectImporter extends Importer
{
    protected static ?string $model = Aspect::class;

    public static function getColumns(): array
    {
        return [
            //
        ];
    }

    public function resolveRecord(): ?Aspect
    {
        $aspect = new Aspect();
        $aspect->school_profile_id = $this->options['school_profile_id'] ?? null;
        
        $userKelas = $this->options['kelas'] ?? null;
        if ($userKelas) {
            $aspect->kelas = $userKelas;
        }

        return $aspect;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your aspect import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
