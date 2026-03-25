<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReflectionsTemplateExport implements WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithEvents
{
    public function headings(): array
    {
        return [
            'Jenis Penilaian',
            'Kategori Predikat',
            'Kelebihan Siswa',
            'Aspek yang perlu ditingkatkan',
            'Rencana tindak lanjut / pengayaan',
            'Kelas'
        ];
    }

    public function title(): string
    {
        return 'Template Import Refleksi';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9D9D9']]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Menambahkan panduan pengisian di kolom sebelah kanan (mulai dari kolom H)
                $sheet->setCellValue('H2', 'PETUNJUK PENGISIAN (KOLOM INI TIDAK AKAN DIIMPORT):');
                $sheet->setCellValue('H3', '1. Kategori Predikat yang diizinkan (Wajib sama persis):');
                $sheet->setCellValue('H4', '   - Sangat Baik (Untuk Nilai 91-100)');
                $sheet->setCellValue('H5', '   - Baik (Untuk Nilai 76-90)');
                $sheet->setCellValue('H6', '   - Cukup (Untuk Nilai 61-75)');
                $sheet->setCellValue('H7', '   - Perlu Bimbingan (Untuk Nilai <60)');
                
                $sheet->setCellValue('H9', '2. Jenis Penilaian hanya ada dua:');
                $sheet->setCellValue('H10', '   - Kinerja');
                $sheet->setCellValue('H11', '   - Proyek');

                $sheet->setCellValue('H13', '3. Cara mengisi lebih dari satu poin refleksi:');
                $sheet->setCellValue('H14', '   Gunakan Enter (Alt+Enter di dalam Excel) ATAU karakter titik koma (;) untuk memisahkan antar poin.');
                $sheet->setCellValue('H15', '   Contoh format sel: Anak sangat aktif; Berani tampil ke depan');

                $sheet->getStyle('H2:H15')->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => '0000FF'],
                        'italic' => true,
                    ]
                ]);
                $sheet->getStyle('H2')->getFont()->setBold(true);
            }
        ];
    }
}
