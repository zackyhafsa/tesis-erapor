<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SubjectsTemplateExport implements WithHeadings, WithTitle, WithStyles
{
    public function headings(): array
    {
        return [
            'Nama Mata Pelajaran',
            'KKTP',
            'Kelas (Opsional untuk Guru)'
        ];
    }

    public function title(): string
    {
        return 'Template Import Mapel';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
