<?php

namespace App\Filament\Pages;

use App\Models\Aspect;
use App\Models\SchoolProfile;
use App\Models\Student;
use App\Models\Subject;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class RekapNilai extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Rekap Nilai';

    protected static ?string $title = 'Rekapitulasi Nilai Kelas';

    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.rekap-nilai';

    // Variabel untuk Filter
    public ?string $subject_id = null;

    public ?string $jenis_penilaian = 'Proyek';

    public ?string $konsep_ketuntasan = 'Tidak Range'; // <-- VARIABEL BARU

    public function mount()
    {
        $this->subject_id = Subject::first()->id ?? null;
        $this->form->fill([
            'subject_id' => $this->subject_id,
            'jenis_penilaian' => $this->jenis_penilaian,
            'konsep_ketuntasan' => $this->konsep_ketuntasan,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('subject_id')
                    ->label('Pilih Mata Pelajaran')
                    ->options(Subject::pluck('nama_mapel', 'id'))
                    ->live()
                    ->afterStateUpdated(fn ($state) => $this->subject_id = $state),

                Select::make('jenis_penilaian')
                    ->label('Pilih Jenis Penilaian')
                    ->options([
                        'Proyek' => 'Proyek',
                        'Kinerja' => 'Kinerja',
                    ])
                    ->live()
                    ->afterStateUpdated(fn ($state) => $this->jenis_penilaian = $state),

                // --- INPUTAN BARU: PILIHAN KONSEP KETUNTASAN ---
                Select::make('konsep_ketuntasan')
                    ->label('Konsep Ketuntasan')
                    ->options([
                        'Tidak Range' => 'Tidak Range (Tuntas / Belum Tuntas)',
                        'Range' => 'Range (Predikat Rentang Nilai)',
                    ])
                    ->live()
                    ->afterStateUpdated(fn ($state) => $this->konsep_ketuntasan = $state),
            ])->columns(3); // Ubah jadi 3 kolom biar sejajar rapi
    }

    // --- 1. MEMBUAT TOMBOL EXPORT DI POJOK KANAN ATAS ---
    protected function getHeaderActions(): array
    {
        return [
            Action::make('cetak_pdf')
                ->label('Export PDF')
                ->color('danger')
                ->icon('heroicon-o-document-text')
                ->action('exportToPdf'),

            Action::make('cetak_excel')
                ->label('Export Excel')
                ->color('success')
                ->icon('heroicon-o-table-cells')
                ->action('exportToExcel'),
        ];
    }

    // --- 2. FUNGSI EXPORT KE PDF ---
    public function exportToPdf()
    {
        if (! $this->subject_id) {
            Notification::make()->warning()->title('Pilih Mapel dulu!')->send();

            return;
        }

        $data = $this->getViewData();
        $data['sekolah'] = SchoolProfile::first();
        $data['jenis_penilaian'] = $this->jenis_penilaian;

        $pdf = Pdf::loadView('cetak.rekap-pdf', $data)->setPaper('a4', 'landscape');

        return response()->streamDownload(fn () => print ($pdf->output()), 'Rekap_Nilai_'.$this->jenis_penilaian.'.pdf');
    }

    // --- 3. FUNGSI EXPORT KE EXCEL ---
    public function exportToExcel()
    {
        if (! $this->subject_id) {
            Notification::make()->warning()->title('Pilih Mapel dulu!')->send();

            return;
        }

        $data = $this->getViewData();
        $data['sekolah'] = SchoolProfile::first();
        $data['jenis_penilaian'] = $this->jenis_penilaian;

        return response()->streamDownload(function () use ($data) {
            echo view('cetak.rekap-excel', $data)->render();
        }, 'Rekap_Nilai_'.$this->jenis_penilaian.'.xls');
    }

    public function getViewData(): array
    {
        if (! $this->subject_id) {
            return ['students' => [], 'aspects' => []];
        }

        $mapel = Subject::find($this->subject_id);
        $kktp = $mapel->kktp ?? 75;

        $aspects = Aspect::where('jenis_penilaian', $this->jenis_penilaian)
            ->with('indicators')
            ->get();

        $students = Student::with(['scores.indicator.aspect'])->get();

        $rekapData = [];

        foreach ($students as $student) {
            $studentScores = $student->scores->whereIn('indicator.aspect_id', $aspects->pluck('id'));

            $aspectScores = [];
            $totalScoreValue = 0;
            $totalIndicators = 0;

            foreach ($aspects as $aspect) {
                $scoresForAspect = $studentScores->where('indicator.aspect_id', $aspect->id);
                $avgSkor = $scoresForAspect->avg('score_value') ?? 0;

                $aspectScores[$aspect->id] = [
                    'skor' => round($avgSkor, 2),
                    'puluhan' => round(($avgSkor / 4) * 100),
                ];

                $totalScoreValue += $scoresForAspect->sum('score_value');
                $totalIndicators += $aspect->indicators->count();
            }

            // Hitung nilai akhir skala 100
            $nilaiAkhir = $totalIndicators > 0 ? round(($totalScoreValue / ($totalIndicators * 4)) * 100) : 0;

            $keputusan = $nilaiAkhir >= $kktp ? 'Pengayaan' : 'Remedial';

            // --- LOGIKA KETUNTASAN BARU (RANGE VS TIDAK RANGE) ---
            if ($this->konsep_ketuntasan === 'Range') {
                if ($nilaiAkhir > 90) {
                    $ketuntasan = 'Sangat Berkembang (SB)';
                } elseif ($nilaiAkhir > 75) {
                    $ketuntasan = 'Berkembang Sesuai Harapan (BSH)';
                } elseif ($nilaiAkhir > 60) {
                    $ketuntasan = 'Mulai Berkembang (MB)';
                } else {
                    $ketuntasan = 'Belum Berkembang (BB)';
                }
            } else {
                $ketuntasan = $nilaiAkhir >= $kktp ? 'Tuntas' : 'Belum Tuntas';
            }

            $rekapData[] = [
                'student' => $student,
                'aspectScores' => $aspectScores,
                'totalSkor' => $totalScoreValue,
                'nilaiAkhir' => $nilaiAkhir,
                'keputusan' => $keputusan,
                'ketuntasan' => $ketuntasan,
            ];
        }

        return [
            'rekapData' => $rekapData,
            'aspects' => $aspects,
            'kktp' => $kktp,
            'mapel' => $mapel,
            'konsep' => $this->konsep_ketuntasan, // Kirim ke tampilan
        ];
    }
}
