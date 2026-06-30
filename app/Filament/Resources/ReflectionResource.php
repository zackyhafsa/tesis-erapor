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
                    ->description('Panduan rentang Nilai puluhan (1-100) untuk mempermudah guru memberikan refleksi yang tepat.')
                    ->schema([
                        Forms\Components\Placeholder::make('Sangat Baik (SB)')
                            ->content('Nilai: 91 - 100'),
                        Forms\Components\Placeholder::make('Berkembang Sesuai Harapan (BSH)')
                            ->content('Nilai: 76 - 90'),
                        Forms\Components\Placeholder::make('Mulai Berkembang (MB)')
                            ->content('Nilai: 61 - 75'),
                        Forms\Components\Placeholder::make('Belum Berkembang (BB)')
                            ->content('Nilai: < 60'),
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
                        '7' => 'Kelas 7',
                        '8' => 'Kelas 8',
                        '9' => 'Kelas 9',
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
                        'Sangat Baik' => 'Sangat Baik (SB) [Nilai 91-100]',
                        'Baik' => 'Berkembang Sesuai Harapan (BSH) [Nilai 76-90]',
                        'Cukup' => 'Mulai Berkembang (MB) [Nilai 61-75]',
                        'Perlu Bimbingan' => 'Belum Berkembang (BB) [Nilai < 60]',
                    ])
                    ->required(),

                Forms\Components\TagsInput::make('kelebihan_siswa')
                    ->label('Kelebihan Siswa (Bisa lebih dari 1)')
                    ->placeholder('Ketik Kelebihan Siswa')
                    ->helperText('Anda bebas memasukkan lebih dari satu temuan refleksi. Pisahkan dengan menekan "Enter".')
                    ->columnSpanFull(),

                Forms\Components\TagsInput::make('aspek_ditingkatkan')
                    ->label('Aspek yang Perlu Ditingkatkan (Bisa lebih dari 1)')
                    ->placeholder('Ketik Aspek yang Perlu Ditingkatkan')
                    ->helperText('Anda bebas memasukkan lebih dari satu temuan refleksi. Pisahkan dengan menekan "Enter".')
                    ->columnSpanFull(),

                Forms\Components\TagsInput::make('tindak_lanjut')
                    ->label('Rencana Tindak Lanjut / Pengayaan (Bisa lebih dari 1)')
                    ->placeholder('Ketik Rencana Tindak Lanjut / Pengayaan')
                    ->helperText('Anda bebas memasukkan lebih dari satu temuan refleksi. Pisahkan dengan menekan "Enter".')
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
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'Sangat Baik' => 'Sangat Baik (91 - 100)',
                        'Baik' => 'Berkembang Sesuai Harapan (76 - 90)',
                        'Cukup' => 'Mulai Berkembang (61 - 75)',
                        'Perlu Bimbingan' => 'Belum Berkembang (< 60)',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Sangat Baik' => 'success',
                        'Baik' => 'info',
                        'Cukup' => 'warning',
                        'Perlu Bimbingan' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('tindak_lanjut')
                    ->label('Tindak Lanjut')
                    ->limit(40)
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
