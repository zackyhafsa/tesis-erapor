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

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Capaian Pembelajaran';

    protected static ?string $modelLabel = 'Capaian Pembelajaran';

    protected static ?string $pluralModelLabel = 'Capaian Pembelajaran';

    protected static ?string $navigationGroup = 'Kurikulum';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kelas')
                    ->label('Kelas')
                    ->datalist([
                        '1A', '1B', '2A', '2B',
                        '3A', '3B', '4A', '4B',
                        '5A', '5B', '6A', '6B',
                        '7A', '7B', '8A', '8B',
                        '9A', '9B',
                    ])
                    ->default(fn () => auth()->user()?->role === 'admin' ? auth()->user()?->kelas : null)
                    ->disabled(fn () => auth()->user()?->role === 'admin')
                    ->dehydrated()
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        $set('subject_id', null);
                        if ($state) {
                            $angkaKelas = (int) $state;
                            $fase = match (true) {
                                $angkaKelas >= 1 && $angkaKelas <= 2 => 'A',
                                $angkaKelas >= 3 && $angkaKelas <= 4 => 'B',
                                $angkaKelas >= 5 && $angkaKelas <= 6 => 'C',
                                $angkaKelas >= 7 && $angkaKelas <= 9 => 'D',
                                default => null,
                            };
                            if ($fase) {
                                $set('fase', $fase);
                            }
                        }
                    }),
                Forms\Components\Select::make('fase')
                    ->label('Fase')
                    ->options([
                        'A' => 'Fase A (Kelas 1-2)',
                        'B' => 'Fase B (Kelas 3-4)',
                        'C' => 'Fase C (Kelas 5-6)',
                        'D' => 'Fase D (Kelas 7-9)',
                    ])
                    ->required()
                    ->default(function () {
                        if (auth()->user()?->role === 'admin' && auth()->user()?->kelas) {
                            $kelas = auth()->user()->kelas;
                            $angkaKelas = (int) $kelas;
                            return match (true) {
                                $angkaKelas >= 1 && $angkaKelas <= 2 => 'A',
                                $angkaKelas >= 3 && $angkaKelas <= 4 => 'B',
                                $angkaKelas >= 5 && $angkaKelas <= 6 => 'C',
                                $angkaKelas >= 7 && $angkaKelas <= 9 => 'D',
                                default => null,
                            };
                        }
                        return null;
                    })
                    ->disabled()
                    ->dehydrated(),

                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'nama_mapel', function ($query, Forms\Get $get) {
                        $query->where('school_profile_id', \Filament\Facades\Filament::getTenant()?->id);
                        
                        if (auth()->user()?->role === 'admin' && auth()->user()?->kelas) {
                            $query->where('kelas', auth()->user()->kelas);
                        } elseif ($get('kelas')) {
                            $query->where('kelas', $get('kelas'));
                        }
                    })
                    ->label('Pilih Mata Pelajaran')
                    ->searchable()
                    ->preload()
                    ->required(),
                
                // Input teks panjang untuk deskripsi CP
                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi Capaian Pembelajaran (CP)')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('fase')
                    ->label('Fase')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'A' => 'info',
                        'B' => 'warning',
                        'C' => 'success',
                        default => 'gray',
                    }),

                // Menampilkan nama mapel yang terhubung
                Tables\Columns\TextColumn::make('subject.nama_mapel')
                    ->label('Mata Pelajaran')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi CP')
                    ->limit(50)
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                        '7' => 'Kelas 7',
                        '8' => 'Kelas 8',
                        '9' => 'Kelas 9',
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
            'index' => Pages\ListLearningOutcomes::route('/'),
            'create' => Pages\CreateLearningOutcome::route('/create'),
            'edit' => Pages\EditLearningOutcome::route('/{record}/edit'),
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
