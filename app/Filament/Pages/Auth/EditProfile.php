<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('filament-panels::pages/auth/edit-profile.form.name.label'))
                    ->required()
                    ->maxLength(255),
                $this->getEmailFormComponent(),
                Select::make('kelas')
                    ->label('Kelas (Tingkat)')
                    ->options([
                        '1' => 'Kelas 1',
                        '2' => 'Kelas 2',
                        '3' => 'Kelas 3',
                        '4' => 'Kelas 4',
                        '5' => 'Kelas 5',
                        '6' => 'Kelas 6',
                    ])
                    ->visible(fn () => auth()->user()?->role === 'admin')
                    ->helperText('Anda bertugas mendata untuk kelas ini. Mengubah kelas di sini akan mengubah seluruh akses data Anda ke kelas yang baru.')
                    ->required(fn () => auth()->user()?->role === 'admin')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
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
                
                TextInput::make('nama_kelas')
                    ->label('Nama Kelas Lengkap / Rombel (Contoh: 1 Khusus)')
                    ->visible(fn () => auth()->user()?->role === 'admin')
                    ->required(fn () => auth()->user()?->role === 'admin'),

                Select::make('fase')
                    ->label('Fase')
                    ->options([
                        'A' => 'Fase A (Kelas 1-2)',
                        'B' => 'Fase B (Kelas 3-4)',
                        'C' => 'Fase C (Kelas 5-6)',
                    ])
                    ->visible(fn () => auth()->user()?->role === 'admin')
                    ->required(fn () => auth()->user()?->role === 'admin')
                    ->default(function (\Filament\Forms\Get $get) {
                        $kelas = $get('kelas');
                        if ($kelas) {
                            $angkaKelas = (int) $kelas;
                            return match (true) {
                                $angkaKelas >= 1 && $angkaKelas <= 2 => 'A',
                                $angkaKelas >= 3 && $angkaKelas <= 4 => 'B',
                                $angkaKelas >= 5 && $angkaKelas <= 6 => 'C',
                                default => null,
                            };
                        }
                        return null;
                    }),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}
