<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReflectionResource\Pages;
use App\Models\Reflection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReflectionResource extends Resource
{
    protected static ?string $model = Reflection::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationLabel = 'Refleksi Guru';

    protected static ?string $modelLabel = 'Refleksi Guru';

    protected static ?string $pluralModelLabel = 'Refleksi Guru';

    protected static ?string $navigationGroup = 'Penilaian';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Rentang Predikat')
                    ->description('Panduan rentang nilai rata-rata (skala 1-4) untuk mempermudah guru memberikan refleksi yang tepat.')
                    ->schema([
                        Forms\Components\Placeholder::make('Sangat Baik (SB)')
                            ->content('Nilai Rata-rata: 3.50 - 4.00'),
                        Forms\Components\Placeholder::make('Baik / BSH')
                            ->content('Nilai Rata-rata: 2.50 - 3.49'),
                        Forms\Components\Placeholder::make('Cukup / MB')
                            ->content('Nilai Rata-rata: 1.50 - 2.49'),
                        Forms\Components\Placeholder::make('Perlu Bimbingan (BB)')
                            ->content('Nilai Rata-rata: < 1.50'),
                    ])->columns(4)->collapsible(),

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

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        // Cuma munculin refleksi yang dibikin di kelas guru tersebut
        if (auth()->user()?->role === 'admin' && auth()->user()?->kelas) {
            $query->where('kelas', auth()->user()->kelas);
        }

        return $query;
    }
}
