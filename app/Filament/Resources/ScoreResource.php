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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'nama')
                    ->label('Pilih Siswa')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Select::make('indicator_id')
                    ->relationship('indicator', 'nama_indikator')
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
                    ->badge() // Membuat skor tampil seperti label berwarna
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'danger',   // Merah
                        2 => 'warning',  // Kuning
                        3 => 'info',     // Biru
                        4 => 'success',  // Hijau
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([
                // Kita bisa menambahkan filter nama siswa nanti di sini
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
        ];
    }
}
