<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReflectionResource\Pages;
use App\Filament\Resources\ReflectionResource\RelationManagers;
use App\Models\Reflection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReflectionResource extends Resource
{
    protected static ?string $model = Reflection::class;

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
                
                Forms\Components\Select::make('kategori_predikat')
                    ->label('Kategori Predikat')
                    ->options([
                        'Sangat Baik' => 'Sangat Baik (SB)',
                        'Baik' => 'Baik / Berkembang Sesuai Harapan (BSH)',
                        'Cukup' => 'Cukup / Mulai Berkembang (MB)',
                        'Perlu Bimbingan' => 'Perlu Bimbingan / Belum Berkembang (BB)',
                    ])
                    ->required(),

                Forms\Components\Textarea::make('kelebihan_siswa')
                    ->label('Kelebihan Siswa')
                    ->rows(3)
                    ->columnSpanFull(),
                
                Forms\Components\Textarea::make('aspek_ditingkatkan')
                    ->label('Aspek yang Perlu Ditingkatkan')
                    ->rows(3)
                    ->columnSpanFull(),
                
                Forms\Components\Textarea::make('tindak_lanjut')
                    ->label('Rencana Tindak Lanjut / Pengayaan')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jenis_penilaian')
                    ->label('Jenis')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('kategori_predikat')
                    ->label('Predikat')
                    ->searchable()
                    ->color(fn (string $state): string => match ($state) {
                        'Sangat Baik' => 'success',
                        'Baik' => 'info',
                        'Cukup' => 'warning',
                        'Perlu Bimbingan' => 'danger',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('tindak_lanjut')
                    ->label('Tindak Lanjut')
                    ->limit(40),
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
            'index' => Pages\ListReflections::route('/'),
            'create' => Pages\CreateReflection::route('/create'),
            'edit' => Pages\EditReflection::route('/{record}/edit'),
        ];
    }
}
