<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScoreResource\Pages;
use App\Models\Score;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ScoreResource extends Resource
{
    protected static ?string $model = Score::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Konsep Penilaian';

    protected static ?string $modelLabel = 'Konsep Penilaian';

    protected static ?string $pluralModelLabel = 'Konsep Penilaian';

    protected static ?string $navigationGroup = 'Penilaian';

    protected static ?int $navigationSort = 3;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'nama', function ($query) {
                        $query->where('school_profile_id', \Filament\Facades\Filament::getTenant()?->id);

                        // Filter agar guru cuma bisa lihat siswa di kelasnya sendiri
                        if (auth()->user()?->role === 'admin' && auth()->user()?->kelas) {
                            $query->where('kelas', auth()->user()->kelas);
                        }
                    })
                    ->label('Pilih Siswa')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Select::make('indicator_id')
                    ->relationship('indicator', 'nama_indikator', function ($query) {
                        $query->where('school_profile_id', \Filament\Facades\Filament::getTenant()?->id);
                    })
                    ->label('Pilih Indikator Penilaian')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Radio::make('score_value')
                    ->label('Berikan Skor (1 - 4)')
                    ->options([
                        1 => '1 - Perlu Bimbingan',
                        2 => '2 - Cukup',
                        3 => '3 - Baik',
                        4 => '4 - Sangat Baik',
                    ])
                    ->inline()
                    ->required()
                    ->columnSpanFull(),

                // --- INI KOLOM CATATAN GURU BARU ---
                Forms\Components\TextInput::make('catatan_guru')
                    ->label('Catatan Guru (Opsional)')
                    ->placeholder('Contoh: Sudah bagus, tingkatkan lagi!')
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.nama')
                    ->label('Nama Siswa')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('indicator.nama_indikator')
                    ->label('Indikator')
                    ->limit(40)
                    ->searchable(),

                Tables\Columns\TextColumn::make('score_value')
                    ->label('Skor')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'danger',
                        2 => 'warning',
                        3 => 'info',
                        4 => 'success',
                        default => 'gray',
                    })
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
            'index' => Pages\ListScores::route('/'),
            'create' => Pages\CreateScore::route('/create'),
            'edit' => Pages\EditScore::route('/{record}/edit'),
            'input-nilai' => Pages\InputNilai::route('/input-nilai'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        // Cuma munculin nilai siswa yang kelasnya sama dengan kelas guru
        if (auth()->user()?->role === 'admin' && auth()->user()?->kelas) {
            $query->whereHas('student', function ($q) {
                $q->where('kelas', auth()->user()->kelas);
            });
        }

        return $query;
    }
}
