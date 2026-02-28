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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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

                // TOMBOL CETAK KITA UBAH JADI POP-UP FORM
                Tables\Actions\Action::make('cetak_rapor')
                    ->label('Cetak Rapor')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->form([
                        \Filament\Forms\Components\Select::make('subject_id')
                            ->label('Pilih Mata Pelajaran')
                            ->options(\App\Models\Subject::pluck('nama_mapel', 'id'))
                            ->searchable()
                            ->required(),

                        \Filament\Forms\Components\Select::make('jenis_penilaian')
                            ->label('Pilih Jenis Penilaian')
                            ->options([
                                'Proyek' => 'Proyek',
                                'Kinerja' => 'Kinerja',
                            ])
                            ->required(),

                        \Filament\Forms\Components\Select::make('cp_ids')
                            ->label('Pilih Capaian Pembelajaran (Bisa lebih dari 1)')
                            ->multiple() // Bisa ceklis banyak
                            ->preload()
                            ->options(function (\Filament\Forms\Get $get) {
                                // Hanya tampilkan CP milik Mapel yang dipilih di atas
                                $mapelId = $get('subject_id');
                                if (! $mapelId) {
                                    return [];
                                }

                                // Catatan: Sesuaikan 'capaian_pembelajaran' dengan nama kolom di database Akang
                                return \App\Models\LearningOutcome::where('subject_id', $mapelId)->pluck('deskripsi', 'id');
                            }),

                        \Filament\Forms\Components\Select::make('tp_ids')
                            ->label('Pilih Tujuan Pembelajaran (Bisa lebih dari 1)')
                            ->multiple() // Bisa ceklis banyak
                            ->preload()
                            ->options(function (\Filament\Forms\Get $get) {
                                // Hanya tampilkan TP milik Mapel yang dipilih di atas
                                $mapelId = $get('subject_id');
                                if (! $mapelId) {
                                    return [];
                                }

                                // Catatan: Sesuaikan 'tujuan_pembelajaran' dengan nama kolom di database Akang
                                return \App\Models\LearningObjective::where('subject_id', $mapelId)->pluck('deskripsi', 'id');
                            }),
                    ])
                    ->action(function ($record, array $data) {
                        // Kirim semua data (termasuk array pilihan TP/CP) ke URL Controller
                        return redirect()->route('cetak.rapor', [
                            'id' => $record->id,
                            'subject_id' => $data['subject_id'],
                            'jenis_penilaian' => $data['jenis_penilaian'],
                            'cp_ids' => $data['cp_ids'] ?? [],
                            'tp_ids' => $data['tp_ids'] ?? [],
                        ]);
                    }),
                // --- TOMBOL INPUT NILAI MATRIX ---
                Tables\Actions\Action::make('input_nilai')
                    ->label('Input Nilai')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')

                    // 1. Membaca Nilai yang sudah ada (Supaya kalau guru mau edit, nilainya gak kosong)
                    ->mountUsing(function (\Filament\Forms\ComponentContainer $form, $record) {
                        $existingScores = \App\Models\Score::where('student_id', $record->id)
                            ->pluck('score_value', 'indicator_id')
                            ->toArray();

                        $formData = [];
                        foreach ($existingScores as $indicatorId => $scoreValue) {
                            $formData['indicator_'.$indicatorId] = $scoreValue;
                        }
                        $form->fill($formData);
                    })

                    // 2. Membuat Form Dinamis (Memanggil seluruh indikator dari database)
                    ->form(function () {
                        $indicators = \App\Models\Indicator::with('aspect')->get();
                        $groupedIndicators = $indicators->groupBy('aspect_id');

                        $schema = [];

                        foreach ($groupedIndicators as $aspectId => $inds) {
                            $aspectName = $inds->first()->aspect->nama_aspek ?? 'Tanpa Aspek';
                            $jenisPenilaian = $inds->first()->aspect->jenis_penilaian ?? '';

                            $fields = [];
                            foreach ($inds as $ind) {
                                // Bikin pilihan ganda untuk setiap indikator
                                $fields[] = \Filament\Forms\Components\Radio::make('indicator_'.$ind->id)
                                    ->label($ind->nama_indikator)
                                    ->helperText($ind->deskripsi_kriteria) // Munculin deskripsi kecil di bawahnya
                                    ->options([
                                        1 => '1 - Perlu Bimbingan',
                                        2 => '2 - Cukup',
                                        3 => '3 - Baik',
                                        4 => '4 - Sangat Baik',
                                    ])
                                    ->inline() // Biar tombol radionya nyamping (nggak menuhin layar)
                                    ->required();
                            }

                            // Kelompokkan dalam kotak (Section) berdasarkan Aspek biar rapi
                            $schema[] = \Filament\Forms\Components\Section::make($aspectName.' ('.$jenisPenilaian.')')
                                ->schema($fields)
                                ->collapsed(); // Kotaknya bisa di-klik untuk buka-tutup
                        }

                        return $schema;
                    })

                    // 3. Menyimpan Data ke Database saat guru klik "Simpan"
                    ->action(function ($record, array $data) {
                        foreach ($data as $key => $value) {
                            if (str_starts_with($key, 'indicator_')) {
                                $indicatorId = str_replace('indicator_', '', $key);

                                // Simpan nilai baru atau Timpa nilai lama jika diedit
                                \App\Models\Score::updateOrCreate(
                                    [
                                        'student_id' => $record->id,
                                        'indicator_id' => $indicatorId,
                                    ],
                                    [
                                        'score_value' => $value,
                                    ]
                                );
                            }
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Berhasil!')
                            ->body('Nilai untuk '.$record->nama.' berhasil disimpan.')
                            ->success()
                            ->send();
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
}
