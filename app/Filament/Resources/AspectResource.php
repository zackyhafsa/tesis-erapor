<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AspectResource\Pages;
use App\Filament\Resources\AspectResource\RelationManagers;
use App\Models\Aspect;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AspectResource extends Resource
{
    protected static ?string $model = Aspect::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                Tables\Columns\TextColumn::make('jenis_penilaian')
                    ->label('Jenis Penilaian')
                    ->sortable()
                    ->searchable()
                    ->badge(), // Tampilan label warna-warni
                
                Tables\Columns\TextColumn::make('nama_aspek')
                    ->label('Aspek Penilaian')
                    ->searchable(),
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
