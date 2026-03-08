<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IndicatorsTemplateExport implements WithHeadings, WithTitle, WithStyles
{
    public function headings(): array
    {
        return [
            'Jenis Penilaian',
            'Nama Aspek',
            'Nama Indikator',
            'Deskripsi Kriteria',
            'Rubrik Skor 1',
            'Rubrik Skor 2',
            'Rubrik Skor 3',
            'Rubrik Skor 4',
            'Kelas (Opsional untuk Guru)'
        ];
    }

    public function title(): string
    {
        return 'Template Import Indikator';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
