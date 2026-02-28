<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolProfileResource\Pages;
use App\Models\SchoolProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SchoolProfileResource extends Resource
{
    protected static ?string $model = SchoolProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Sekolah')
                    ->columns(3) // Dibagi jadi 3 kolom agar rapi menyamping
                    ->schema([
                        Forms\Components\TextInput::make('nama_sekolah')->required(),
                        Forms\Components\TextInput::make('npsn')->label('NPSN'),
                        Forms\Components\TextInput::make('nss')->label('NSS'),
                    ]),

                Forms\Components\Section::make('Alamat Sekolah')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('alamat')->columnSpanFull(),
                        Forms\Components\TextInput::make('desa'),
                        Forms\Components\TextInput::make('kecamatan'),
                        Forms\Components\TextInput::make('kabupaten'),
                        Forms\Components\TextInput::make('provinsi'),
                        Forms\Components\TextInput::make('kode_pos'),
                    ]),

                Forms\Components\Section::make('Informasi Kelas & Akademik')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('kelas')->label('Kelas (Contoh: I)'),
                        Forms\Components\TextInput::make('fase')->label('Fase (Contoh: A)'),
                        Forms\Components\TextInput::make('semester')->label('Semester (Contoh: I (Satu))'),
                        Forms\Components\TextInput::make('tahun_pelajaran')->label('Tahun Pelajaran'),
                    ]),

                Forms\Components\Section::make('Penandatangan & Rapor')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('kepala_sekolah')->label('Nama Kepala Sekolah'),
                        Forms\Components\TextInput::make('nip_kepsek')->label('NIP Kepala Sekolah'),
                        Forms\Components\TextInput::make('guru_kelas')->label('Nama Guru Kelas'),
                        Forms\Components\TextInput::make('nip_guru')->label('NIP Guru Kelas'),
                        Forms\Components\TextInput::make('tempat_cetak')->label('Tempat Rapor (Contoh: Kertajati)'),
                        Forms\Components\DatePicker::make('tanggal_cetak')->label('Tanggal Cetak Rapor'),
                    ]),

                \Filament\Forms\Components\Section::make('Logo Sekolah & Dinas')
                    ->schema([
                        \Filament\Forms\Components\FileUpload::make('logo_kiri')
                            ->label('Logo Kiri (Kabupaten/Dinas)')
                            ->image()
                            ->directory('logos') // Gambar akan disimpan di folder storage/app/public/logos
                            ->maxSize(2048), // Maksimal 2MB

                        \Filament\Forms\Components\FileUpload::make('logo_kanan')
                            ->label('Logo Kanan (Sekolah)')
                            ->image()
                            ->directory('logos')
                            ->maxSize(2048),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_sekolah')
                    ->label('Sekolah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kepala_sekolah')
                    ->label('Kepala Sekolah'),
                Tables\Columns\TextColumn::make('guru_kelas')
                    ->label('Guru Kelas'),
                Tables\Columns\TextColumn::make('tahun_pelajaran')
                    ->label('Tahun Pelajaran'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSchoolProfiles::route('/'),
            'create' => Pages\CreateSchoolProfile::route('/create'),
            'edit' => Pages\EditSchoolProfile::route('/{record}/edit'),
        ];
    }
}
