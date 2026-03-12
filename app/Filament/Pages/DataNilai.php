<?php

namespace App\Filament\Pages;

use App\Models\Aspect;
use App\Models\Score;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DataNilai extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationLabel = 'Data Nilai';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?string $title = 'Data Nilai Siswa';

    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.data-nilai';

    public function table(Table $table): Table
    {
        $tenantId = Filament::getTenant()?->id;

        return $table
            ->query(
                Score::query()
                    ->where('school_profile_id', $tenantId)
                    ->when(
                        auth()->user()?->role === 'admin' && auth()->user()?->kelas,
                        fn (Builder $q) => $q->whereHas('student', fn ($sq) => $sq->where('kelas', auth()->user()->kelas))
                    )
                    ->with(['student', 'indicator.aspect', 'subject'])
            )
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('student.nama')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject.nama_mapel')
                    ->label('Mata Pelajaran')
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('indicator.aspect.nama_aspek')
                    ->label('Aspek')
                    ->sortable()
                    ->description(fn (Score $record) => $record->indicator?->aspect?->jenis_penilaian),

                Tables\Columns\TextColumn::make('indicator.nama_indikator')
                    ->label('Indikator')
                    ->wrap()
                    ->limit(50),

                Tables\Columns\TextColumn::make('score_value')
                    ->label('Skor')
                    ->alignCenter()
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'danger',
                        2 => 'warning',
                        3 => 'info',
                        4 => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => '1 - Perlu Bimbingan',
                        2 => '2 - Cukup',
                        3 => '3 - Baik',
                        4 => '4 - Sangat Baik',
                        default => (string) $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Input')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('student_id')
                    ->label('Siswa')
                    ->relationship('student', 'nama', fn (Builder $query) => $query
                        ->where('school_profile_id', $tenantId)
                        ->when(
                            auth()->user()?->role === 'admin' && auth()->user()?->kelas,
                            fn ($q) => $q->where('kelas', auth()->user()->kelas)
                        )
                    )
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('subject_id')
                    ->label('Mata Pelajaran')
                    ->relationship('subject', 'nama_mapel', fn (Builder $query) => $query
                        ->where('school_profile_id', $tenantId)
                        ->when(
                            auth()->user()?->role === 'admin' && auth()->user()?->kelas,
                            fn ($q) => $q->where('kelas', auth()->user()->kelas)
                        )
                    )
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('jenis_penilaian')
                    ->label('Jenis Penilaian')
                    ->options([
                        'Proyek' => 'Proyek',
                        'Kinerja' => 'Kinerja',
                    ])
                    ->query(fn (Builder $query, array $data) => $query->when(
                        $data['value'],
                        fn ($q) => $q->whereHas('indicator.aspect', fn ($sq) => $sq->where('jenis_penilaian', $data['value']))
                    )),

                Tables\Filters\SelectFilter::make('aspect_id')
                    ->label('Aspek')
                    ->options(function () use ($tenantId) {
                        return Aspect::where('school_profile_id', $tenantId)
                            ->when(
                                auth()->user()?->role === 'admin' && auth()->user()?->kelas,
                                fn ($q) => $q->where('kelas', auth()->user()->kelas)
                            )
                            ->pluck('nama_aspek', 'id');
                    })
                    ->query(fn (Builder $query, array $data) => $query->when(
                        $data['value'],
                        fn ($q) => $q->whereHas('indicator', fn ($sq) => $sq->where('aspect_id', $data['value']))
                    ))
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Data Nilai')
                    ->modalDescription('Yakin ingin menghapus data nilai ini? Tindakan ini tidak bisa dibatalkan.')
                    ->modalSubmitActionLabel('Ya, Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus yang dipilih')
                        ->modalHeading('Hapus Data Nilai')
                        ->modalDescription('Yakin ingin menghapus semua data nilai yang dipilih?'),
                ]),
            ])
            ->emptyStateHeading('Belum ada data nilai')
            ->emptyStateDescription('Gunakan filter di atas atau input nilai melalui menu Data Siswa.')
            ->emptyStateIcon('heroicon-o-clipboard-document')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
