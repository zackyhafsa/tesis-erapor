<?php

namespace App\Filament\Widgets;

use App\Models\Indicator;
use App\Models\Score;
use App\Models\Student;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class BottomStudentsWidget extends BaseWidget
{
    protected static ?string $heading = 'Peringkat 5 Siswa Terendah';

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        $userRole = auth()->user()?->role;
        $userKelas = auth()->user()?->kelas;

        $baseQuery = Student::query()
            ->where('school_profile_id', $tenantId)
            ->whereHas('scores')
            ->withAvg('scores as rata_rata_skor', 'score_value');

        if ($userRole === 'admin' && $userKelas) {
            $baseQuery->where('kelas', $userKelas);
        }

        // Ambil ID 5 terendah
        $ids = (clone $baseQuery)->orderBy('rata_rata_skor', 'asc')->limit(5)->pluck('id')->toArray();

        // Query final untuk ditampilkan di tabel
        $query = Student::query()
            ->whereIn('id', $ids)
            ->withAvg('scores as rata_rata_skor', 'score_value');

        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('rank')
                    ->label('No')
                    ->rowIndex(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('rata_rata_skor')
                    ->label('Rata-rata (Skala 4)')
                    ->getStateUsing(fn (Student $record): string => number_format($record->rata_rata_skor ?? 0, 2))
                    ->alignCenter()
                    ->badge()
                    ->sortable()
                    ->color(function (Student $record): string {
                        $nilai = (($record->rata_rata_skor ?? 0) / 4) * 100;
                        if ($nilai > 90) return 'success';
                        if ($nilai > 75) return 'info';
                        if ($nilai > 60) return 'warning';
                        return 'danger';
                    }),

                Tables\Columns\TextColumn::make('nilai_100')
                    ->label('Nilai (Skala 100)')
                    ->getStateUsing(fn (Student $record): string => (string) round((($record->rata_rata_skor ?? 0) / 4) * 100))
                    ->alignCenter()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('predikat')
                    ->label('Predikat')
                    ->getStateUsing(function (Student $record): string {
                        $nilai = (($record->rata_rata_skor ?? 0) / 4) * 100;
                        if ($nilai > 90) return 'Sangat Baik (SB)';
                        if ($nilai > 75) return 'Berkembang Sesuai Harapan (BSH)';
                        if ($nilai > 60) return 'Mulai Berkembang (MB)';
                        return 'Belum Berkembang (BB)';
                    })
                    ->badge()
                    ->color(function (Student $record): string {
                        $nilai = (($record->rata_rata_skor ?? 0) / 4) * 100;
                        if ($nilai > 90) return 'success';
                        if ($nilai > 75) return 'info';
                        if ($nilai > 60) return 'warning';
                        return 'danger';
                    }),

                Tables\Columns\TextColumn::make('jumlah_nilai')
                    ->label('Jml Dinilai')
                    ->getStateUsing(function (Student $record) use ($tenantId, $userRole, $userKelas): string {
                        $totalInd = \App\Models\Indicator::where('school_profile_id', $tenantId)
                            ->when($userRole === 'admin' && $userKelas, fn ($q) => $q->where('kelas', $userKelas))
                            ->count();
                        $totalSubj = \App\Models\Subject::where('school_profile_id', $tenantId)
                            ->when($userRole === 'admin' && $userKelas, fn ($q) => $q->where('kelas', $userKelas))
                            ->count();
                        $target = $totalInd * $totalSubj;
                        $dinilai = $record->scores()->count();
                        return $dinilai . '/' . $target;
                    })
                    ->alignCenter()
                    ->color(function (Student $record) use ($tenantId, $userRole, $userKelas): string {
                        $totalInd = \App\Models\Indicator::where('school_profile_id', $tenantId)
                            ->when($userRole === 'admin' && $userKelas, fn ($q) => $q->where('kelas', $userKelas))
                            ->count();
                        $totalSubj = \App\Models\Subject::where('school_profile_id', $tenantId)
                            ->when($userRole === 'admin' && $userKelas, fn ($q) => $q->where('kelas', $userKelas))
                            ->count();
                        $target = $totalInd * $totalSubj;
                        $dinilai = $record->scores()->count();
                        return $dinilai >= $target && $target > 0 ? 'success' : 'warning';
                    }),
            ])
            ->defaultSort('rata_rata_skor', 'asc')
            ->paginated(false);
    }
}
