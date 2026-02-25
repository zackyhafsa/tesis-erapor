<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IndicatorResource\Pages;
use App\Filament\Resources\IndicatorResource\RelationManagers;
use App\Models\Indicator;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IndicatorResource extends Resource
{
    protected static ?string $model = Indicator::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Dropdown untuk memilih Aspek
                Forms\Components\Select::make('aspect_id')
                    ->relationship('aspect', 'nama_aspek')
                    ->label('Pilih Aspek Penilaian')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('nama_indikator')
                    ->label('Nama Indikator (Contoh: Memahami tujuan kegiatan kerja)')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('deskripsi_kriteria')
                    ->label('Deskripsi Kriteria')
                    ->columnSpanFull(),

                // Kotak input untuk Rubrik Skor 1 sampai 4
                Forms\Components\Textarea::make('catatan_skor_1')
                    ->label('Rubrik Skor 1 (Perlu Bimbingan)')
                    ->rows(3),
                
                Forms\Components\Textarea::make('catatan_skor_2')
                    ->label('Rubrik Skor 2 (Cukup)')
                    ->rows(3),
                
                Forms\Components\Textarea::make('catatan_skor_3')
                    ->label('Rubrik Skor 3 (Baik)')
                    ->rows(3),
                
                Forms\Components\Textarea::make('catatan_skor_4')
                    ->label('Rubrik Skor 4 (Sangat Baik)')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('aspect.nama_aspek')
                    ->label('Aspek')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('nama_indikator')
                    ->label('Indikator')
                    ->limit(40) // Batasi teks agar tabel tidak penuh
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
            'index' => Pages\ListIndicators::route('/'),
            'create' => Pages\CreateIndicator::route('/create'),
            'edit' => Pages\EditIndicator::route('/{record}/edit'),
        ];
    }
}
