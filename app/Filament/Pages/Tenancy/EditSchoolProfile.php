<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditSchoolProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Profil Sekolah';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Identitas Sekolah')
                    ->columns(3)
                    ->schema([
                        TextInput::make('nama_sekolah')->required(),
                        TextInput::make('npsn')->label('NPSN'),
                        TextInput::make('nss')->label('NSS'),
                    ]),

                Section::make('Alamat Sekolah')
                    ->columns(3)
                    ->schema([
                        TextInput::make('alamat')->columnSpanFull(),
                        TextInput::make('desa'),
                        TextInput::make('kecamatan'),
                        TextInput::make('kabupaten'),
                        TextInput::make('provinsi'),
                        TextInput::make('kode_pos'),
                    ]),

                Section::make('Informasi Akademik')
                    ->columns(2)
                    ->schema([
                        TextInput::make('semester')->label('Semester (Contoh: I (Satu))'),
                        TextInput::make('tahun_pelajaran')->label('Tahun Pelajaran'),
                    ]),

                Section::make('Penandatangan & Rapor')
                    ->columns(2)
                    ->schema([
                        TextInput::make('kepala_sekolah')->label('Nama Kepala Sekolah'),
                        TextInput::make('nip_kepsek')->label('NIP Kepala Sekolah'),
                        TextInput::make('guru_kelas')->label('Nama Guru Kelas'),
                        TextInput::make('nip_guru')->label('NIP Guru Kelas'),
                        TextInput::make('tempat_cetak')->label('Tempat Rapor (Contoh: Kertajati)'),
                        DatePicker::make('tanggal_cetak')->label('Tanggal Cetak Rapor'),
                    ]),

                Section::make('Logo Sekolah & Dinas')
                    ->schema([
                        FileUpload::make('logo_kiri')
                            ->label('Logo Kiri (Kabupaten/Dinas)')
                            ->image()
                            ->directory('logos') // Gambar akan disimpan di folder storage/app/public/logos
                            ->maxSize(1024), // Maksimal 1MB

                        FileUpload::make('logo_kanan')
                            ->label('Logo Kanan (Sekolah)')
                            ->image()
                            ->directory('logos')
                            ->maxSize(1024), // Maksimal 1MB
                    ])->columns(2),
            ]);
    }
}
