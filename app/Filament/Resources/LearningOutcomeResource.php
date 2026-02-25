<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LearningOutcomeResource\Pages;
use App\Filament\Resources\LearningOutcomeResource\RelationManagers;
use App\Models\LearningOutcome;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LearningOutcomeResource extends Resource
{
    protected static ?string $model = LearningOutcome::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Dropdown yang otomatis mengambil data dari tabel Subject
                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'nama_mapel')
                    ->label('Pilih Mata Pelajaran')
                    ->searchable()
                    ->preload()
                    ->required(),
                
                // Input teks panjang untuk deskripsi CP
                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi Capaian Pembelajaran (CP)')
                    ->required()
                    ->columnSpanFull(), // Agar kotaknya lebar penuh
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Menampilkan nama mapel yang terhubung
                Tables\Columns\TextColumn::make('subject.nama_mapel')
                    ->label('Mata Pelajaran')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi CP')
                    ->limit(50) // Dibatasi 50 huruf agar tabel tidak terlalu panjang
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
            'index' => Pages\ListLearningOutcomes::route('/'),
            'create' => Pages\CreateLearningOutcome::route('/create'),
            'edit' => Pages\EditLearningOutcome::route('/{record}/edit'),
        ];
    }
}
