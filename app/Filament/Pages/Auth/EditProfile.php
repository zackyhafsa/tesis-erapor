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
                    ->label('Kelas yang Diajar')
                    ->options([
                        '1A' => '1A', '1B' => '1B',
                        '2A' => '2A', '2B' => '2B',
                        '3A' => '3A', '3B' => '3B',
                        '4A' => '4A', '4B' => '4B',
                        '5A' => '5A', '5B' => '5B',
                        '6A' => '6A', '6B' => '6B',
                    ])
                    ->visible(fn () => auth()->user()?->role === 'admin')
                    ->helperText('Anda bertugas mendata untuk kelas ini. Mengubah kelas di sini akan mengubah seluruh akses data Anda ke kelas yang baru.')
                    ->required(fn () => auth()->user()?->role === 'admin'),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}
