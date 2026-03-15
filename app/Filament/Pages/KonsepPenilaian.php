<?php

namespace App\Filament\Pages;

use App\Models\Aspect;
use App\Models\LearningObjective;
use App\Models\LearningOutcome;
use App\Models\PenilaianConfig;
use App\Models\Subject;
use Filament\Facades\Filament;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class KonsepPenilaian extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Konsep Penilaian';

    protected static ?string $navigationGroup = 'Penilaian';

    protected static ?string $title = 'Konsep Penilaian';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.konsep-penilaian';

    public ?string $subject_id = null;

    public ?string $jenis_penilaian = null;

    public ?array $aspect_ids = [];

    public ?array $cp_ids = [];

    public ?array $tp_ids = [];

    public ?string $load_preset = null;

    public function mount(): void
    {
        $tenantId = Filament::getTenant()?->id;
        $userId = auth()->id();

        // Load existing config
        $config = PenilaianConfig::where('school_profile_id', $tenantId)
            ->where('user_id', $userId)
            ->first();

        if ($config) {
            $this->form->fill([
                'subject_id' => $config->subject_id ? (string) $config->subject_id : null,
                'jenis_penilaian' => $config->jenis_penilaian,
                'aspect_ids' => $config->aspect_ids ?? [],
                'cp_ids' => $config->cp_ids ?? [],
                'tp_ids' => $config->tp_ids ?? [],
            ]);
        }
    }

    public function form(Form $form): Form
    {
        $tenantId = Filament::getTenant()?->id;

        return $form
            ->schema([
                Section::make('Riwayat Pengaturan (Satu Klik)')
                    ->description('Pilih pengaturan sebelumnya untuk mengisi form otomatis tanpa harus menyeting ulang (CP, TP, dll).')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Select::make('load_preset')
                            ->label('Pilih Riwayat Pengaturan')
                            ->options(function () {
                                $config = PenilaianConfig::where('school_profile_id', Filament::getTenant()?->id)
                                    ->where('user_id', auth()->id())
                                    ->first();
                                if (! $config || empty($config->presets)) {
                                    return [];
                                }
                                $opts = [];
                                foreach ($config->presets as $key => $preset) {
                                    $opts[$key] = $preset['name'] ?? 'Riwayat';
                                }

                                return $opts;
                            })
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (! $state) {
                                    return;
                                }
                                $config = PenilaianConfig::where('school_profile_id', Filament::getTenant()?->id)
                                    ->where('user_id', auth()->id())
                                    ->first();
                                if ($config && isset($config->presets[$state])) {
                                    $preset = $config->presets[$state];
                                    $set('subject_id', $preset['subject_id'] ?? null);
                                    $set('jenis_penilaian', $preset['jenis_penilaian'] ?? null);
                                    $set('aspect_ids', $preset['aspect_ids'] ?? []);
                                    $set('cp_ids', $preset['cp_ids'] ?? []);
                                    $set('tp_ids', $preset['tp_ids'] ?? []);
                                }
                            }),
                    ])->collapsible(),

                Section::make('Pengaturan Penilaian')
                    ->description('Atur mata pelajaran, capaian pembelajaran, tujuan pembelajaran, jenis penilaian, dan aspek yang akan digunakan saat guru menginput nilai siswa.')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->schema([
                        Select::make('subject_id')
                            ->label('Mata Pelajaran')
                            ->options(function () use ($tenantId) {
                                $query = Subject::where('school_profile_id', $tenantId);

                                if (auth()->user()?->role === 'admin' && auth()->user()?->kelas) {
                                    $query->where('kelas', auth()->user()->kelas);
                                }

                                return $query->pluck('nama_mapel', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function () {
                                $this->cp_ids = [];
                                $this->tp_ids = [];
                            }),

                        Select::make('cp_ids')
                            ->label('Capaian Pembelajaran (CP)')
                            ->multiple()
                            ->preload()
                            ->options(function () use ($tenantId) {
                                if (! $this->subject_id) {
                                    return [];
                                }

                                return LearningOutcome::where('school_profile_id', $tenantId)
                                    ->where('subject_id', $this->subject_id)
                                    ->pluck('deskripsi', 'id');
                            })
                            ->live(),

                        Select::make('tp_ids')
                            ->label('Tujuan Pembelajaran (TP)')
                            ->multiple()
                            ->preload()
                            ->options(function () use ($tenantId) {
                                if (! $this->subject_id) {
                                    return [];
                                }

                                return LearningObjective::where('school_profile_id', $tenantId)
                                    ->where('subject_id', $this->subject_id)
                                    ->pluck('deskripsi', 'id');
                            })
                            ->live(),

                        Select::make('jenis_penilaian')
                            ->label('Jenis Penilaian')
                            ->options([
                                'Proyek' => 'Proyek',
                                'Kinerja' => 'Kinerja',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn () => $this->aspect_ids = []),

                        Select::make('aspect_ids')
                            ->label('Aspek Penilaian')
                            ->placeholder('Semua Aspek (Opsional)')
                            ->helperText('Kosongkan untuk menilai semua aspek sekaligus.')
                            ->multiple()
                            ->options(function () use ($tenantId) {
                                if (! $this->jenis_penilaian) {
                                    return [];
                                }

                                $query = Aspect::where('school_profile_id', $tenantId)
                                    ->where('jenis_penilaian', $this->jenis_penilaian);

                                if (auth()->user()?->role === 'admin' && auth()->user()?->kelas) {
                                    $query->where('kelas', auth()->user()->kelas);
                                }

                                return $query->pluck('nama_aspek', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->live(),
                    ])->columns(2),
            ]);
    }

    public function save(): void
    {
        $tenantId = Filament::getTenant()?->id;
        $userId = auth()->id();

        $config = PenilaianConfig::updateOrCreate(
            [
                'school_profile_id' => $tenantId,
                'user_id' => $userId,
            ],
            [
                'subject_id' => $this->subject_id,
                'jenis_penilaian' => $this->jenis_penilaian,
                'aspect_ids' => $this->aspect_ids ?? [],
                'cp_ids' => $this->cp_ids ?? [],
                'tp_ids' => $this->tp_ids ?? [],
            ]
        );

        $presets = $config->presets ?? [];
        $subjectName = Subject::find($this->subject_id)?->nama_mapel ?? 'Mata Pelajaran';
        $key = $this->subject_id.'_'.str_replace(' ', '', $this->jenis_penilaian);

        $presets[$key] = [
            'name' => $subjectName.' - '.$this->jenis_penilaian,
            'subject_id' => $this->subject_id,
            'jenis_penilaian' => $this->jenis_penilaian,
            'aspect_ids' => $this->aspect_ids ?? [],
            'cp_ids' => $this->cp_ids ?? [],
            'tp_ids' => $this->tp_ids ?? [],
        ];

        $config->update(['presets' => $presets]);

        $this->load_preset = null; // Reset form pemilihan setelah disimpan

        Notification::make()
            ->success()
            ->title('Berhasil!')
            ->body('Konsep penilaian berhasil disimpan. Setting ini akan otomatis terisi saat guru menginput nilai siswa.')
            ->send();
    }
}
