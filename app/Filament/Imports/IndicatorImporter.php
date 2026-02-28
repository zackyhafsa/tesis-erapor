<?php

namespace App\Filament\Imports;

use App\Models\Indicator;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class IndicatorImporter extends Importer
{
    protected static ?string $model = Indicator::class;

    public static function getColumns(): array
    {
        return [
            // 1. Membaca aspect_id dari CSV berupa angka
            \Filament\Actions\Imports\ImportColumn::make('aspect_id')
                ->label('ID Aspek')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),

            // 2. Membaca nama indikator
            \Filament\Actions\Imports\ImportColumn::make('nama_indikator')
                ->label('Nama Indikator')
                ->requiredMapping()
                ->rules(['required', 'string']),

            // 3. Membaca deskripsi kriteria
            \Filament\Actions\Imports\ImportColumn::make('deskripsi_kriteria')
                ->label('Deskripsi Kriteria')
                ->rules(['nullable', 'string']),

            \Filament\Actions\Imports\ImportColumn::make('catatan_skor_1')
                ->label('Rubrik Skor 1')
                ->rules(['nullable', 'string']),

            \Filament\Actions\Imports\ImportColumn::make('catatan_skor_2')
                ->label('Rubrik Skor 2')
                ->rules(['nullable', 'string']),

            \Filament\Actions\Imports\ImportColumn::make('catatan_skor_3')
                ->label('Rubrik Skor 3')
                ->rules(['nullable', 'string']),

            \Filament\Actions\Imports\ImportColumn::make('catatan_skor_4')
                ->label('Rubrik Skor 4')
                ->rules(['nullable', 'string']),
        ];
    }

    public function resolveRecord(): ?Indicator
    {
        // return Indicator::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Indicator;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your indicator import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
