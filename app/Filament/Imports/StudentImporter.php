<?php

namespace App\Filament\Imports;

use App\Models\Student;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class StudentImporter extends Importer
{
    protected static ?string $model = Student::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nipd')
                ->label('NIPD / NISN')
                ->rules(['nullable', 'string']),

            ImportColumn::make('nama')
                ->label('Nama Lengkap')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),

            ImportColumn::make('kelas')
                ->label('Kelas (Contoh: 1A)')
                ->rules(['nullable', 'string']),

            ImportColumn::make('fase')
                ->label('Fase (A/B/C)')
                ->rules(['nullable', 'string', 'in:A,B,C']),

            ImportColumn::make('jenis_kelamin')
                ->label('Jenis Kelamin (L/P)')
                ->rules(['nullable', 'in:L,P']),

            ImportColumn::make('tempat_lahir')
                ->label('Tempat Lahir')
                ->rules(['nullable', 'string']),

            ImportColumn::make('tanggal_lahir')
                ->label('Tanggal Lahir')
                ->rules(['nullable', 'date']),

            ImportColumn::make('agama')
                ->label('Agama')
                ->rules(['nullable', 'string']),

            ImportColumn::make('pendidikan_sebelumnya')
                ->label('Pendidikan Sebelumnya')
                ->rules(['nullable', 'string']),

            ImportColumn::make('nama_ayah')
                ->label('Nama Ayah')
                ->rules(['nullable', 'string']),

            ImportColumn::make('pekerjaan_ayah')
                ->label('Pekerjaan Ayah')
                ->rules(['nullable', 'string']),

            ImportColumn::make('nama_ibu')
                ->label('Nama Ibu')
                ->rules(['nullable', 'string']),

            ImportColumn::make('pekerjaan_ibu')
                ->label('Pekerjaan Ibu')
                ->rules(['nullable', 'string']),

            ImportColumn::make('jalan')
                ->label('Jalan / Dusun')
                ->rules(['nullable', 'string']),

            ImportColumn::make('desa')
                ->label('Desa / Kelurahan')
                ->rules(['nullable', 'string']),

            ImportColumn::make('kecamatan')
                ->label('Kecamatan')
                ->rules(['nullable', 'string']),

            ImportColumn::make('kabupaten')
                ->label('Kabupaten')
                ->rules(['nullable', 'string']),

            ImportColumn::make('provinsi')
                ->label('Provinsi')
                ->rules(['nullable', 'string']),

            ImportColumn::make('nama_wali')
                ->label('Nama Wali')
                ->rules(['nullable', 'string']),

            ImportColumn::make('pekerjaan_wali')
                ->label('Pekerjaan Wali')
                ->rules(['nullable', 'string']),

            ImportColumn::make('alamat_wali')
                ->label('Alamat Wali')
                ->rules(['nullable', 'string']),
        ];
    }

    public function resolveRecord(): ?Student
    {
        $tenantId = $this->options['school_profile_id'] ?? null;
        $userKelas = $this->options['kelas'] ?? null;

        // Jika NIPD ada, update data yang sudah ada; jika tidak, buat baru
        if (! empty($this->data['nipd'])) {
            $student = Student::firstOrNew([
                'nipd' => $this->data['nipd'],
                'school_profile_id' => $tenantId,
            ]);
        } else {
            $student = new Student;
        }

        $student->school_profile_id = $tenantId;

        // Jika yang mengimport adalah guru (punya data kelas), paksa masuk ke kelas guru tersebut
        if ($userKelas) {
            $student->kelas = $userKelas;
        }

        return $student;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import data siswa berhasil! '.number_format($import->successful_rows).' '.str('baris')->plural($import->successful_rows).' berhasil diimport.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('baris')->plural($failedRowsCount).' gagal diimport.';
        }

        return $body;
    }
}
