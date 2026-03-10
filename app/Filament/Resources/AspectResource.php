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
                Forms\Components\Select::make('kelas')
                    ->label('Kelas (Tingkat)')
                    ->options([
                        '1' => 'Kelas 1',
                        '2' => 'Kelas 2',
                        '3' => 'Kelas 3',
                        '4' => 'Kelas 4',
                        '5' => 'Kelas 5',
                        '6' => 'Kelas 6',
                    ])
                    ->default(fn () => auth()->user()?->role === 'admin' ? auth()->user()?->kelas : null)
                    ->disabled(fn () => auth()->user()?->role === 'admin')
                    ->dehydrated()
                    ->required(),
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
                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable(),
            ])
            ->headerActions([
                // Excel Import dipindah ke halaman ListAspects.php
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kelas')
                    ->label('Filter Kelas')
                    ->options([
                        '1' => 'Kelas 1',
                        '2' => 'Kelas 2',
                        '3' => 'Kelas 3',
                        '4' => 'Kelas 4',
                        '5' => 'Kelas 5',
                        '6' => 'Kelas 6',
                    ])
                    ->hidden(fn () => auth()->user()?->role === 'admin'),
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
    
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        
        // Cuma munculin Aspek dari kelas guru yang login (admin)
        if (auth()->user()?->role === 'admin' && auth()->user()?->kelas) {
            $query->where('kelas', auth()->user()->kelas);
        }
        
        return $query;
    }
}
