<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LearningObjectivesTemplateExport implements WithHeadings, WithTitle, WithStyles
{
    public function headings(): array
    {
        return [
            'Nama Mata Pelajaran',
            'Deskripsi TP',
            'Kelas (Opsional untuk Guru)'
        ];
    }

    public function title(): string
    {
        return 'Template Import TP';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
