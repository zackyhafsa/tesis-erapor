<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReflectionsTemplateExport implements WithHeadings, WithTitle, WithStyles
{
    public function headings(): array
    {
        return [
            'Jenis Penilaian',
            'Kategori Predikat',
            'Kelebihan Siswa',
            'Aspek yang perlu ditingkatkan',
            'Rencana tindak lanjut / pengayaan',
            'Kelas (Opsional untuk Guru)'
        ];
    }

    public function title(): string
    {
        return 'Template Import Refleksi';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
