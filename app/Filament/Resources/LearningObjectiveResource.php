<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LearningObjectiveResource\Pages;
use App\Filament\Resources\LearningObjectiveResource\RelationManagers;
use App\Models\LearningObjective;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LearningObjectiveResource extends Resource
{
    protected static ?string $model = LearningObjective::class;

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    protected static ?string $navigationLabel = 'Tujuan Pembelajaran';

    protected static ?string $modelLabel = 'Tujuan Pembelajaran';

    protected static ?string $pluralModelLabel = 'Tujuan Pembelajaran';

    protected static ?string $navigationGroup = 'Kurikulum';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'nama_mapel', fn ($query) => $query->where('school_profile_id', \Filament\Facades\Filament::getTenant()?->id))
                    ->label('Pilih Mata Pelajaran')
                    ->searchable()
                    ->preload()
                    ->required(),
                
                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi Tujuan Pembelajaran (TP)')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject.nama_mapel')
                    ->label('Mata Pelajaran')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi TP')
                    ->limit(50)
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
            'index' => Pages\ListLearningObjectives::route('/'),
            'create' => Pages\CreateLearningObjective::route('/create'),
            'edit' => Pages\EditLearningObjective::route('/{record}/edit'),
        ];
    }
}
