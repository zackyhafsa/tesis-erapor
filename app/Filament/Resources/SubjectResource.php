<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubjectResource\Pages;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Mata Pelajaran';

    protected static ?string $modelLabel = 'Mata Pelajaran';

    protected static ?string $pluralModelLabel = 'Mata Pelajaran';

    protected static ?string $navigationGroup = 'Kurikulum';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_mapel')
                    ->label('Nama Mata Pelajaran')
                    ->required()
                    ->maxLength(255),
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
                    ->default(fn () => auth()->user()?->role === 'admin' ? auth()->user()?->kelas : null)
                    ->disabled(fn () => auth()->user()?->role === 'admin')
                    ->dehydrated()
                    ->required(),
                Forms\Components\TextInput::make('kktp')
                    ->label('KKTP (Contoh: 75)')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_mapel')
                    ->label('Mata Pelajaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kktp')
                    ->label('KKTP')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListSubjects::route('/'),
            'create' => Pages\CreateSubject::route('/create'),
            'edit' => Pages\EditSubject::route('/{record}/edit'),
        ];
    }
    
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        
        // Scope hanya untuk role admin (guru)
        if (auth()->user()?->role === 'admin' && auth()->user()?->kelas) {
            $query->where('kelas', auth()->user()->kelas);
        }
        
        return $query;
    }
}
