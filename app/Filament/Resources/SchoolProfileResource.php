<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolProfileResource\Pages;
use App\Filament\Resources\SchoolProfileResource\RelationManagers;
use App\Models\SchoolProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SchoolProfileResource extends Resource
{
    protected static ?string $model = SchoolProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_sekolah')
                    ->label('Nama Sekolah')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('kepala_sekolah')
                    ->label('Nama Kepala Sekolah')
                    ->maxLength(255),
                Forms\Components\TextInput::make('nip_kepsek')
                    ->label('NIP Kepala Sekolah')
                    ->maxLength(255),
                Forms\Components\TextInput::make('guru_kelas')
                    ->label('Nama Guru Kelas')
                    ->maxLength(255),
                Forms\Components\TextInput::make('nip_guru')
                    ->label('NIP Guru Kelas')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tahun_pelajaran')
                    ->label('Tahun Pelajaran (Contoh: 2025/2026)')
                    ->maxLength(255),
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
