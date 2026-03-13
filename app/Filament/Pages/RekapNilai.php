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

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?string $title = 'Rekapitulasi Nilai Kelas';

    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.rekap-nilai';

    // Variabel untuk Filter
    public ?string $subject_id = null;

    public ?string $jenis_penilaian = 'Proyek';

    public ?string $kelas_filter = null;

    public ?string $konsep_ketuntasan = 'Tidak Range';

    // Rentang nilai untuk konsep Range
    public ?int $range_tuntas_min = 75;

    public ?int $range_tuntas_max = 100;

    public ?int $range_tidak_tuntas_min = 0;

    public ?int $range_tidak_tuntas_max = 74;

    public function mount()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        
        // Default kelas filter jika guru yang login
        if (auth()->user()?->role === 'admin') {
            $this->kelas_filter = auth()->user()?->kelas;
        }

        // Load saved settings from PenilaianConfig (Konsep Penilaian)
        $config = \App\Models\PenilaianConfig::where('school_profile_id', $tenantId)
            ->where('user_id', auth()->id())
            ->first();

        if ($config) {
            $this->subject_id = $config->subject_id ? (string) $config->subject_id : null;
            $this->jenis_penilaian = $config->jenis_penilaian ?? 'Proyek';
            $this->konsep_ketuntasan = $config->konsep_ketuntasan ?? 'Tidak Range';
            $this->range_tuntas_min = $config->range_tuntas_min ?? 75;
            $this->range_tuntas_max = $config->range_tuntas_max ?? 100;
            $this->range_tidak_tuntas_min = $config->range_tidak_tuntas_min ?? 0;
            $this->range_tidak_tuntas_max = $config->range_tidak_tuntas_max ?? 74;
        }

        // Fallback jika subject_id belum ada di config
        if (! $this->subject_id) {
            $query = Subject::where('school_profile_id', $tenantId);
            if (auth()->user()?->role === 'admin' && auth()->user()?->kelas) {
                $query->where('kelas', auth()->user()->kelas);
            }
            $this->subject_id = $query->first()?->id ?? null;
        }
        
        $this->form->fill([
            'subject_id' => $this->subject_id,
            'jenis_penilaian' => $this->jenis_penilaian,
            'kelas_filter' => $this->kelas_filter,
            'konsep_ketuntasan' => $this->konsep_ketuntasan,
            'range_tuntas_min' => $this->range_tuntas_min,
            'range_tuntas_max' => $this->range_tuntas_max,
            'range_tidak_tuntas_min' => $this->range_tidak_tuntas_min,
            'range_tidak_tuntas_max' => $this->range_tidak_tuntas_max,
        ]);
    }

    /**
     * Simpan settingan ketuntasan ke PenilaianConfig.
     */
    protected function saveKetuntasanSettings(): void
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;

        \App\Models\PenilaianConfig::updateOrCreate(
            [
                'school_profile_id' => $tenantId,
                'user_id' => auth()->id(),
            ],
            [
                'konsep_ketuntasan' => $this->konsep_ketuntasan,
                'range_tuntas_min' => $this->range_tuntas_min,
                'range_tuntas_max' => $this->range_tuntas_max,
                'range_tidak_tuntas_min' => $this->range_tidak_tuntas_min,
                'range_tidak_tuntas_max' => $this->range_tidak_tuntas_max,
            ]
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('subject_id')
                    ->label('Pilih Mata Pelajaran')
                    ->options(function () {
                        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
                        $query = Subject::where('school_profile_id', $tenantId);

                        if (auth()->user()?->role === 'admin' && auth()->user()?->kelas) {
                            $query->where('kelas', auth()->user()->kelas);
                        }

                        return $query->pluck('nama_mapel', 'id');
                    })
                    ->live()
                    ->afterStateUpdated(fn ($state) => $this->subject_id = $state),

                Select::make('kelas_filter')
                    ->label('Filter Kelas')
                    ->options([
                        '1A' => '1A', '1B' => '1B',
                        '2A' => '2A', '2B' => '2B',
                        '3A' => '3A', '3B' => '3B',
                        '4A' => '4A', '4B' => '4B',
                        '5A' => '5A', '5B' => '5B',
                        '6A' => '6A', '6B' => '6B',
                    ])
                    ->placeholder('Semua Kelas')
                    ->hidden(fn () => auth()->user()?->role === 'admin') // Sembunyikan untuk guru
                    ->live()
                    ->afterStateUpdated(fn ($state) => $this->kelas_filter = $state),

                Select::make('jenis_penilaian')
                    ->label('Pilih Jenis Penilaian')
                    ->options([
                        'Proyek' => 'Proyek',
                        'Kinerja' => 'Kinerja',
                    ])
                    ->live()
                    ->afterStateUpdated(fn ($state) => $this->jenis_penilaian = $state),

                Select::make('konsep_ketuntasan')
                    ->label('Konsep Ketuntasan')
                    ->options([
                        'Tidak Range' => 'Tidak Range (Otomatis dari KKTP)',
                        'Range' => 'Range (Tentukan Rentang Nilai)',
                    ])
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->konsep_ketuntasan = $state;
                        $this->saveKetuntasanSettings();
                    }),

                // Input rentang nilai untuk Tuntas (hanya muncul saat konsep = Range)
                \Filament\Forms\Components\Fieldset::make('Rentang Nilai Tuntas')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('range_tuntas_min')
                            ->label('Nilai Min')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->range_tuntas_min = (int) $state;
                                $this->saveKetuntasanSettings();
                            }),
                        \Filament\Forms\Components\TextInput::make('range_tuntas_max')
                            ->label('Nilai Max')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->range_tuntas_max = (int) $state;
                                $this->saveKetuntasanSettings();
                            }),
                    ])
                    ->columns(2)
                    ->visible(fn (\Filament\Forms\Get $get) => $get('konsep_ketuntasan') === 'Range'),

                // Input rentang nilai untuk Tidak Tuntas (hanya muncul saat konsep = Range)
                \Filament\Forms\Components\Fieldset::make('Rentang Nilai Tidak Tuntas')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('range_tidak_tuntas_min')
                            ->label('Nilai Min')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->range_tidak_tuntas_min = (int) $state;
                                $this->saveKetuntasanSettings();
                            }),
                        \Filament\Forms\Components\TextInput::make('range_tidak_tuntas_max')
                            ->label('Nilai Max')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->range_tidak_tuntas_max = (int) $state;
                                $this->saveKetuntasanSettings();
                            }),
                    ])
                    ->columns(2)
                    ->visible(fn (\Filament\Forms\Get $get) => $get('konsep_ketuntasan') === 'Range'),
            ])->columns(3);
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
        $data['sekolah'] = \Filament\Facades\Filament::getTenant();
        $data['jenis_penilaian'] = $this->jenis_penilaian;
        $data['kelasFilter'] = $this->kelas_filter;

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
        $data['sekolah'] = \Filament\Facades\Filament::getTenant();
        $data['jenis_penilaian'] = $this->jenis_penilaian;
        $data['kelasFilter'] = $this->kelas_filter;

        return response()->streamDownload(function () use ($data) {
            echo view('cetak.rekap-excel', $data)->render();
        }, 'Rekap_Nilai_'.$this->jenis_penilaian.'.xls');
    }

    public function getViewData(): array
    {
        if (! $this->subject_id) {
            return [
                'rekapData' => [],
                'aspects' => [],
                'kktp' => 75,
                'mapel' => null,
                'konsep' => $this->konsep_ketuntasan,
                'rangeTuntasMin' => $this->range_tuntas_min,
                'rangeTuntasMax' => $this->range_tuntas_max,
                'rangeTidakTuntasMin' => $this->range_tidak_tuntas_min,
                'rangeTidakTuntasMax' => $this->range_tidak_tuntas_max,
            ];
        }

        $mapel = Subject::find($this->subject_id);
        $kktp = $mapel->kktp ?? 75;
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;

        // Kunci filter kelas jika user adalah admin (guru)
        if (auth()->user()?->role === 'admin' && auth()->user()?->kelas) {
            $this->kelas_filter = auth()->user()->kelas;
        }

        $aspects = Aspect::where('school_profile_id', $tenantId)
            ->where('jenis_penilaian', $this->jenis_penilaian)
            // Aspek sekarang nggak dikunci per kelas lagi, jadi filter ini dihapus
            ->with('indicators')
            ->get();

        $studentsQuery = Student::where('school_profile_id', $tenantId);
        if ($this->kelas_filter) {
            $studentsQuery->where('kelas', $this->kelas_filter);
        }
        $students = $studentsQuery->with(['scores.indicator.aspect'])->get();

        $rekapData = [];

        foreach ($students as $student) {
            $studentScores = $student->scores
                ->where('subject_id', (int) $this->subject_id)
                ->whereIn('indicator.aspect_id', $aspects->pluck('id'));

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

            // --- LOGIKA KETUNTASAN (RANGE VS TIDAK RANGE) ---
            if ($this->konsep_ketuntasan === 'Range') {
                // Gunakan rentang nilai yang diinput user
                if ($nilaiAkhir >= $this->range_tuntas_min && $nilaiAkhir <= $this->range_tuntas_max) {
                    $ketuntasan = 'Tuntas';
                } else {
                    $ketuntasan = 'Tidak Tuntas';
                }
            } else {
                $ketuntasan = $nilaiAkhir >= $kktp ? 'Tuntas' : 'Tidak Tuntas';
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
            'konsep' => $this->konsep_ketuntasan,
            'rangeTuntasMin' => $this->range_tuntas_min,
            'rangeTuntasMax' => $this->range_tuntas_max,
            'rangeTidakTuntasMin' => $this->range_tidak_tuntas_min,
            'rangeTidakTuntasMax' => $this->range_tidak_tuntas_max,
        ];
    }
}
