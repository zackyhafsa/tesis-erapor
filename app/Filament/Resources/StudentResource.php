<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Data Siswa dan Penilaian';

    protected static ?string $modelLabel = 'Siswa';

    protected static ?string $pluralModelLabel = 'Data Siswa dan Penilaian';

    protected static ?string $navigationGroup = 'Data Sekolah';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Biodata Siswa')
                    ->tabs([
                        // TAB 1: DATA PRIBADI
                        Forms\Components\Tabs\Tab::make('Data Pribadi')
                            ->icon('heroicon-o-user')
                            ->columns(2)
                            ->schema([
                                Forms\Components\TextInput::make('nipd')->label('NIPD / NISN'),
                                Forms\Components\TextInput::make('nama')->label('Nama Lengkap')->required(),
                                Forms\Components\Select::make('kelas')
                                    ->label('Kelas (Tingkat)')
                                    ->options([
                                        '1' => 'Kelas 1',
                                        '2' => 'Kelas 2',
                                        '3' => 'Kelas 3',
                                        '4' => 'Kelas 4',
                                        '5' => 'Kelas 5',
                                        '6' => 'Kelas 6',
                                    ])
                                    ->required()
                                    ->default(fn () => auth()->user()?->role === 'admin' ? auth()->user()?->kelas : null)
                                    ->disabled(fn () => auth()->user()?->role === 'admin')
                                    ->dehydrated()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        // Otomatis isi fase berdasarkan kelas
                                        if ($state) {
                                            $angkaKelas = (int) $state;

                                            $fase = match (true) {
                                                $angkaKelas >= 1 && $angkaKelas <= 2 => 'A',
                                                $angkaKelas >= 3 && $angkaKelas <= 4 => 'B',
                                                $angkaKelas >= 5 && $angkaKelas <= 6 => 'C',
                                                default => null,
                                            };

                                            if ($fase) {
                                                $set('fase', $fase);
                                            }
                                        }
                                    }),

                                Forms\Components\TextInput::make('nama_kelas')
                                    ->label('Nama Kelas / Rombel')
                                    ->required()
                                    ->default(fn () => auth()->user()?->role === 'admin' ? auth()->user()?->nama_kelas : null)
                                    ->disabled(fn () => auth()->user()?->role === 'admin')
                                    ->dehydrated(),
                                Forms\Components\Select::make('fase')
                                    ->label('Fase')
                                    ->options([
                                        'A' => 'Fase A (Kelas 1-2)',
                                        'B' => 'Fase B (Kelas 3-4)',
                                        'C' => 'Fase C (Kelas 5-6)',
                                    ])
                                    ->required()
                                    ->default(function () {
                                        if (auth()->user()?->role === 'admin' && auth()->user()?->kelas) {
                                            $kelas = auth()->user()->kelas;
                                            $angkaKelas = (int) $kelas;

                                            return match (true) {
                                                $angkaKelas >= 1 && $angkaKelas <= 2 => 'A',
                                                $angkaKelas >= 3 && $angkaKelas <= 4 => 'B',
                                                $angkaKelas >= 5 && $angkaKelas <= 6 => 'C',
                                                default => null,
                                            };
                                        }

                                        return null;
                                    })
                                    ->disabled(fn () => auth()->user()?->role === 'admin')
                                    ->dehydrated(),
                                Forms\Components\Select::make('jenis_kelamin')->label('Jenis Kelamin')->options(['L' => 'Laki-laki', 'P' => 'Perempuan']),
                                Forms\Components\TextInput::make('agama')->label('Agama'),
                                Forms\Components\TextInput::make('tempat_lahir')->label('Tempat Lahir'),
                                Forms\Components\DatePicker::make('tanggal_lahir')->label('Tanggal Lahir'),
                                Forms\Components\TextInput::make('pendidikan_sebelumnya')->label('Pendidikan Sebelumnya')->columnSpanFull(),
                            ]),

                        // TAB 2: DATA ORANG TUA
                        Forms\Components\Tabs\Tab::make('Data Orang Tua')
                            ->icon('heroicon-o-users')
                            ->columns(2)
                            ->schema([
                                Forms\Components\TextInput::make('nama_ayah')->label('Nama Ayah'),
                                Forms\Components\TextInput::make('pekerjaan_ayah')->label('Pekerjaan Ayah'),
                                Forms\Components\TextInput::make('nama_ibu')->label('Nama Ibu'),
                                Forms\Components\TextInput::make('pekerjaan_ibu')->label('Pekerjaan Ibu'),
                                Forms\Components\Section::make('Alamat Orang Tua')
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('jalan')->label('Jalan / Dusun')->columnSpanFull(),
                                        Forms\Components\TextInput::make('desa')->label('Desa / Kelurahan'),
                                        Forms\Components\TextInput::make('kecamatan')->label('Kecamatan'),
                                        Forms\Components\TextInput::make('kabupaten')->label('Kabupaten'),
                                        Forms\Components\TextInput::make('provinsi')->label('Provinsi'),
                                    ]),
                            ]),

                        // TAB 3: DATA WALI
                        Forms\Components\Tabs\Tab::make('Data Wali')
                            ->icon('heroicon-o-user-plus')
                            ->columns(1)
                            ->schema([
                                Forms\Components\TextInput::make('nama_wali')->label('Nama Wali'),
                                Forms\Components\TextInput::make('pekerjaan_wali')->label('Pekerjaan Wali'),
                                Forms\Components\TextInput::make('alamat_wali')->label('Alamat Wali'),
                            ]),
                    ])->columnSpanFull(), // Agar Tab-nya penuh satu layar
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Menampilkan kolom NIPD (Bisa dicari/Search)
                Tables\Columns\TextColumn::make('nipd')
                    ->label('NIPD')
                    ->searchable(),

                // Menampilkan kolom Nama (Bisa dicari/Search)
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Lengkap')
                    ->searchable(),

                // Menampilkan kolom Kelas
                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),

                // Menampilkan kolom Nama Kelas
                Tables\Columns\TextColumn::make('nama_kelas')
                    ->label('Rombel')
                    ->sortable()
                    ->searchable(),

                // Menampilkan kolom Fase
                Tables\Columns\TextColumn::make('fase')
                    ->label('Fase')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'A' => 'info',
                        'B' => 'warning',
                        'C' => 'success',
                        default => 'gray',
                    }),

                // Menampilkan kolom Jenis Kelamin
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('L/P')
                    ->badge() // Membuat tampilannya seperti label berwarna
                    ->color(fn (string $state): string => match ($state) {
                        'L' => 'info',
                        'P' => 'success',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                // TOMBOL INPUT NILAI - Link ke halaman Input Nilai dengan siswa terpilih
                Tables\Actions\Action::make('input_nilai')
                    ->label('Input Nilai')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->url(fn ($record) => \App\Filament\Resources\ScoreResource::getUrl('input-nilai', ['student_id' => $record->id])),

                // TOMBOL CETAK RAPOR - Buka di tab baru
                Tables\Actions\Action::make('cetak_rapor')
                    ->label('Cetak Rapor')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->fillForm(function () {
                        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
                        $config = \App\Models\PenilaianConfig::where('school_profile_id', $tenantId)
                            ->where('user_id', auth()->id())
                            ->first();

                        if (! $config) {
                            return [];
                        }

                        return [
                            'subject_id' => $config->subject_id ? (string) $config->subject_id : null,
                            'jenis_penilaian' => $config->jenis_penilaian,
                            'cp_ids' => $config->cp_ids ?? [],
                            'tp_ids' => $config->tp_ids ?? [],
                            'aspect_ids' => $config->aspect_ids ?? [],
                        ];
                    })
                    ->form([
                        \Filament\Forms\Components\Select::make('subject_id')
                            ->label('Pilih Mata Pelajaran')
                            ->options(function () {
                                $tenantId = \Filament\Facades\Filament::getTenant()?->id;
                                $query = \App\Models\Subject::where('school_profile_id', $tenantId);

                                if (auth()->user()?->role === 'admin' && auth()->user()?->kelas) {
                                    $query->where('kelas', auth()->user()->kelas);
                                }

                                return $query->pluck('nama_mapel', 'id');
                            })
                            ->searchable()
                            ->required()
                            ->live(),

                        \Filament\Forms\Components\Select::make('jenis_penilaian')
                            ->label('Pilih Jenis Penilaian')
                            ->options([
                                'Proyek' => 'Proyek',
                                'Kinerja' => 'Kinerja',
                            ])
                            ->required()
                            ->live(),

                        \Filament\Forms\Components\Select::make('cp_ids')
                            ->label('Pilih Capaian Pembelajaran (Bisa lebih dari 1)')
                            ->multiple()
                            ->preload()
                            ->options(function (\Filament\Forms\Get $get) {
                                $mapelId = $get('subject_id');
                                if (! $mapelId) {
                                    return [];
                                }
                                $tenantId = \Filament\Facades\Filament::getTenant()?->id;

                                return \App\Models\LearningOutcome::where('school_profile_id', $tenantId)
                                    ->where('subject_id', $mapelId)
                                    ->pluck('deskripsi', 'id');
                            }),

                        \Filament\Forms\Components\Select::make('tp_ids')
                            ->label('Pilih Tujuan Pembelajaran (Bisa lebih dari 1)')
                            ->multiple()
                            ->preload()
                            ->options(function (\Filament\Forms\Get $get) {
                                $mapelId = $get('subject_id');
                                if (! $mapelId) {
                                    return [];
                                }
                                $tenantId = \Filament\Facades\Filament::getTenant()?->id;

                                return \App\Models\LearningObjective::where('school_profile_id', $tenantId)
                                    ->where('subject_id', $mapelId)
                                    ->pluck('deskripsi', 'id');
                            }),

                        \Filament\Forms\Components\Select::make('aspect_ids')
                            ->label('Pilih Aspek yang Akan Dicetak')
                            ->multiple()
                            ->preload()
                            ->options(function (\Filament\Forms\Get $get) {
                                $jenis = $get('jenis_penilaian');
                                if (! $jenis) {
                                    return [];
                                }
                                $tenantId = \Filament\Facades\Filament::getTenant()?->id;

                                $query = \App\Models\Aspect::where('school_profile_id', $tenantId)
                                    ->where('jenis_penilaian', $jenis);

                                if (auth()->user()?->role === 'admin' && auth()->user()?->kelas) {
                                    $query->where('kelas', auth()->user()->kelas);
                                }

                                return $query->pluck('nama_aspek', 'id');
                            }),
                    ])
                    ->action(function ($record, array $data, $livewire) {
                        // Bangun URL dengan query params
                        $params = [
                            'subject_id' => $data['subject_id'],
                            'jenis_penilaian' => $data['jenis_penilaian'],
                        ];

                        if (! empty($data['cp_ids'])) {
                            foreach ($data['cp_ids'] as $i => $cpId) {
                                $params["cp_ids[$i]"] = $cpId;
                            }
                        }

                        if (! empty($data['tp_ids'])) {
                            foreach ($data['tp_ids'] as $i => $tpId) {
                                $params["tp_ids[$i]"] = $tpId;
                            }
                        }

                        if (! empty($data['aspect_ids'])) {
                            foreach ($data['aspect_ids'] as $i => $aspectId) {
                                $params["aspect_ids[$i]"] = $aspectId;
                            }
                        }

                        $url = route('cetak.rapor', ['id' => $record->id]).'?'.http_build_query($params);

                        // Buka di tab baru menggunakan JavaScript (target="_blank")
                        $livewire->js("window.open('{$url}', '_blank')");
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);

    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        // Scope hanya untuk role admin (guru)
        if (auth()->user()?->role === 'admin' && auth()->user()?->kelas) {
            $query->where('kelas', auth()->user()->kelas);
        }

        return $query;
    }
}
