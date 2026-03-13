<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsTemplateExport implements WithHeadings, WithTitle, WithStyles
{
    public function headings(): array
    {
        return [
            'NIPD / NISN',
            'Nama Lengkap',
            'Kelas',
            'Nama Kelas Rombel',
            'Fase',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Agama',
            'Pendidikan Sebelumnya',
            'Nama Ayah',
            'Pekerjaan Ayah',
            'Nama Ibu',
            'Pekerjaan Ibu',
            'Jalan / Dusun',
            'Desa / Kelurahan',
            'Kecamatan',
            'Kabupaten',
            'Provinsi',
            'Nama Wali',
            'Pekerjaan Wali',
            'Alamat Wali'
        ];
    }

    public function title(): string
    {
        return 'Template Import Siswa';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
