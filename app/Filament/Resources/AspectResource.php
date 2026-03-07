<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AspectResource\Pages;
use App\Models\Aspect;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AspectResource extends Resource
{
    protected static ?string $model = Aspect::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Aspek Penilaian';

    protected static ?string $modelLabel = 'Aspek Penilaian';

    protected static ?string $pluralModelLabel = 'Aspek Penilaian';

    protected static ?string $navigationGroup = 'Penilaian';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kelas')
                    ->label('Kelas')
                    ->options([
                        '1A' => '1A', '1B' => '1B',
                        '2A' => '2A', '2B' => '2B',
                        '3A' => '3A', '3B' => '3B',
                        '4A' => '4A', '4B' => '4B',
                        '5A' => '5A', '5B' => '5B',
                        '6A' => '6A', '6B' => '6B',
                    ])
                    ->required(),

                Forms\Components\Select::make('jenis_penilaian')
                    ->label('Jenis Penilaian')
                    ->options([
                        'Kinerja' => 'Penilaian Kinerja',
                        'Proyek' => 'Penilaian Proyek',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('nama_aspek')
                    ->label('Nama Aspek (Contoh: Pemahaman Tugas)')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('jenis_penilaian')
                    ->label('Jenis Penilaian')
                    ->sortable()
                    ->searchable()
                    ->badge(), // Tampilan label warna-warni

                Tables\Columns\TextColumn::make('nama_aspek')
                    ->label('Aspek Penilaian')
                    ->searchable(),
            ])
            ->headerActions([
                // INI DIA TOMBOL IMPORT-NYA
                Tables\Actions\ImportAction::make()
                    ->importer(\App\Filament\Imports\AspectImporter::class)
                    ->label('Import Aspek dari CSV')
                    ->color('primary')
                    ->icon('heroicon-o-arrow-up-tray'),
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
            'index' => Pages\ListAspects::route('/'),
            'create' => Pages\CreateAspect::route('/create'),
            'edit' => Pages\EditAspect::route('/{record}/edit'),
        ];
    }
}
