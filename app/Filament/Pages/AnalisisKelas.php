<?php

namespace App\Filament\Pages;

use App\Models\Aspect;
use App\Models\Score;
use App\Models\Student;
use App\Models\Subject;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class AnalisisKelas extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $navigationLabel = 'Analisis Kelas';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?string $title = 'Dashboard Analisis Penilaian Kelas';

    protected static ?int $navigationSort = 6;

    protected static string $view = 'filament.pages.analisis-kelas';

    public ?string $subject_id = null;

    public ?string $jenis_penilaian = 'Proyek';

    public ?string $mode_tampil_aspek = 'semua';

    public function mount()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;

        $config = \App\Models\PenilaianConfig::where('school_profile_id', $tenantId)
            ->where('user_id', auth()->id())
            ->first();

        if ($config) {
            $this->subject_id = $config->subject_id ? (string) $config->subject_id : null;
            $this->jenis_penilaian = $config->jenis_penilaian ?? 'Proyek';
        }

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
            'mode_tampil_aspek' => $this->mode_tampil_aspek,
        ]);
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
                    ->required()
                    ->selectablePlaceholder(false)
                    ->live()
                    ->afterStateUpdated(fn ($state) => $this->subject_id = $state),

                Select::make('jenis_penilaian')
                    ->label('Pilih Jenis Penilaian')
                    ->options([
                        'Proyek' => 'Proyek',
                        'Kinerja' => 'Kinerja',
                    ])
                    ->required()
                    ->selectablePlaceholder(false)
                    ->live()
                    ->afterStateUpdated(fn ($state) => $this->jenis_penilaian = $state),

                Select::make('mode_tampil_aspek')
                    ->label('Tampilan Aspek')
                    ->options([
                        'semua' => 'Seluruh Aspek (Default)',
                        'dinilai' => 'Hanya Aspek yang Sudah Dinilai',
                    ])
                    ->default('semua')
                    ->required()
                    ->selectablePlaceholder(false)
                    ->live()
                    ->afterStateUpdated(fn ($state) => $this->mode_tampil_aspek = $state),
            ])->columns(3);
    }

    public function getViewData(): array
    {
        if (! $this->subject_id) {
            return ['stats' => null];
        }

        $mapel = Subject::find($this->subject_id);
        $kktp = $mapel->kktp ?? 75;
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;

        // 1. Ambil Indikator Sesuai Filter
        $aspects = Aspect::where('school_profile_id', $tenantId)->where('jenis_penilaian', $this->jenis_penilaian)->with('indicators')->get();

        $kelasFilter = (auth()->user()?->role === 'admin') ? auth()->user()?->kelas : null;

        $studentIds = Student::where('school_profile_id', $tenantId)
            ->when($kelasFilter, fn($q) => $q->where('kelas', $kelasFilter))
            ->pluck('id');

        if ($this->mode_tampil_aspek === 'dinilai') {
            $gradedAspectIds = \App\Models\Score::where('subject_id', $this->subject_id)
                ->whereIn('student_id', $studentIds)
                ->whereNotNull('score_value')
                ->with('indicator')
                ->get()
                ->pluck('indicator.aspect_id')
                ->filter()
                ->unique()
                ->values()
                ->toArray();
                
            $aspects = $aspects->whereIn('id', $gradedAspectIds)->values();
        }

        $indicators = $aspects->flatMap->indicators;
        $indicatorIds = $indicators->pluck('id');

        // 2. Hitung Rata-rata Per Indikator (Peta Klasikal)
        $allScores = Score::where('subject_id', $this->subject_id)
            ->whereIn('indicator_id', $indicatorIds)
            ->whereIn('student_id', $studentIds)
            ->get()->groupBy('indicator_id');

        $petaIndikator = [];
        foreach ($indicators as $ind) {
            $scores = $allScores->get($ind->id);
            $avg = $scores ? $scores->avg('score_value') : 0;
            $petaIndikator[] = [
                'nama' => $ind->nama_indikator,
                'rata_rata' => round($avg, 2),
            ];
        }

        // Urutkan untuk mencari Terkuat dan Terlemah
        $kumpulanIndikator = collect($petaIndikator);
        $terkuat = $kumpulanIndikator->where('rata_rata', '>', 0)->sortByDesc('rata_rata')->take(3)->values();
        $terlemah = $kumpulanIndikator->where('rata_rata', '>', 0)->sortBy('rata_rata')->take(3)->values();

        // 3. Hitung Rata-rata Per Aspek (Aspek Kompetensi Kelas)
        $aspekKompetensi = [];
        foreach ($aspects as $aspect) {
            $aspekIndicatorIds = $aspect->indicators->pluck('id');
            $aspekScores = Score::where('subject_id', $this->subject_id)
                ->whereIn('indicator_id', $aspekIndicatorIds)
                ->whereIn('student_id', $studentIds)
                ->get();
            $avg = $aspekScores->count() > 0 ? $aspekScores->avg('score_value') : 0;
            $aspekKompetensi[] = [
                'nama_aspek' => $aspect->nama_aspek,
                'rata_rata' => round($avg, 2),
                'rata_rata_100' => round(($avg / 4) * 100),
            ];
        }

        // 4. Hitung Distribusi Predikat Siswa + Pengayaan/Remedial
        $students = Student::where('school_profile_id', $tenantId)
            ->when($kelasFilter, fn($q) => $q->where('kelas', $kelasFilter))
            ->with(['scores' => function ($q) use ($indicatorIds) {
                $q->where('subject_id', $this->subject_id)
                  ->whereIn('indicator_id', $indicatorIds);
            }])->get();

        $distribusi = [
            'SB' => 0,
            'BSH' => 0,
            'MB' => 0,
            'BB' => 0,
        ];

        $totalSiswa = $students->count();
        $jumlahIndikator = $indicators->count();

        $pengayaanRemedial = [];

        foreach ($students as $student) {
            $totalSkor = $student->scores->sum('score_value');
            $nilaiAkhir = $jumlahIndikator > 0 ? round(($totalSkor / ($jumlahIndikator * 4)) * 100) : 0;

            if ($nilaiAkhir > 90) {
                $distribusi['SB']++;
                $predikat = 'Sangat Berkembang (SB)';
            } elseif ($nilaiAkhir > 75) {
                $distribusi['BSH']++;
                $predikat = 'Berkembang Sesuai Harapan (BSH)';
            } elseif ($nilaiAkhir > 60) {
                $distribusi['MB']++;
                $predikat = 'Mulai Berkembang (MB)';
            } else {
                $distribusi['BB']++;
                $predikat = 'Belum Berkembang (BB)';
            }

            $keputusan = $nilaiAkhir >= $kktp ? 'Pengayaan' : 'Remedial';

            $pengayaanRemedial[] = [
                'nama' => $student->nama,
                'nipd' => $student->nipd,
                'nilai_akhir' => $nilaiAkhir,
                'predikat' => $predikat,
                'keputusan' => $keputusan,
            ];
        }

        // Urutkan: Remedial dulu, lalu Pengayaan
        $pengayaanRemedial = collect($pengayaanRemedial)->sortBy('nilai_akhir')->values();

        return [
            'stats' => true,
            'mapel' => $mapel,
            'kktp' => $kktp,
            'petaIndikator' => $kumpulanIndikator,
            'terkuat' => $terkuat,
            'terlemah' => $terlemah,
            'aspekKompetensi' => collect($aspekKompetensi),
            'distribusi' => $distribusi,
            'totalSiswa' => $totalSiswa > 0 ? $totalSiswa : 1,
            'pengayaanRemedial' => $pengayaanRemedial,
        ];
    }
}
