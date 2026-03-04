<?php

namespace App\Filament\Widgets;

use App\Models\Indicator;
use App\Models\Score;
use App\Models\Student;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopBottomStudentsWidget extends BaseWidget
{
    protected static ?string $heading = 'Peringkat Peserta Didik (5 Tertinggi & 5 Terendah)';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;

        return $table
            ->query(
                Student::query()->where('school_profile_id', $tenantId)->whereHas('scores')
            )
            ->columns([
                Tables\Columns\TextColumn::make('rank')
                    ->label('No')
                    ->rowIndex(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Peserta Didik')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('rata_rata_skor')
                    ->label('Rata-rata (Skala 4)')
                    ->getStateUsing(function (Student $record): string {
                        $avg = $record->scores()->avg('score_value');
                        return number_format($avg ?? 0, 2);
                    })
                    ->alignCenter()
                    ->badge()
                    ->color(function (Student $record): string {
                        $avg = $record->scores()->avg('score_value');
                        $nilai = (($avg ?? 0) / 4) * 100;
                        if ($nilai > 90) return 'success';
                        if ($nilai > 75) return 'info';
                        if ($nilai > 60) return 'warning';
                        return 'danger';
                    }),

                Tables\Columns\TextColumn::make('nilai_100')
                    ->label('Nilai (Skala 100)')
                    ->getStateUsing(function (Student $record): string {
                        $avg = $record->scores()->avg('score_value');
                        return round((($avg ?? 0) / 4) * 100);
                    })
                    ->alignCenter()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('predikat')
                    ->label('Predikat')
                    ->getStateUsing(function (Student $record): string {
                        $avg = $record->scores()->avg('score_value');
                        $nilai = (($avg ?? 0) / 4) * 100;
                        if ($nilai > 90) return 'Sangat Berkembang';
                        if ($nilai > 75) return 'Berkembang Sesuai Harapan';
                        if ($nilai > 60) return 'Mulai Berkembang';
                        return 'Belum Berkembang';
                    })
                    ->badge()
                    ->color(function (Student $record): string {
                        $avg = $record->scores()->avg('score_value');
                        $nilai = (($avg ?? 0) / 4) * 100;
                        if ($nilai > 90) return 'success';
                        if ($nilai > 75) return 'info';
                        if ($nilai > 60) return 'warning';
                        return 'danger';
                    }),

                Tables\Columns\TextColumn::make('jumlah_nilai')
                    ->label('Jml Dinilai')
                    ->getStateUsing(function (Student $record): string {
                        $totalInd = Indicator::where('school_profile_id', \Filament\Facades\Filament::getTenant()?->id)->count();
                        $dinilai = $record->scores()->count();
                        return $dinilai . '/' . $totalInd;
                    })
                    ->alignCenter()
                    ->color(function (Student $record): string {
                        $totalInd = Indicator::where('school_profile_id', \Filament\Facades\Filament::getTenant()?->id)->count();
                        $dinilai = $record->scores()->count();
                        return $dinilai >= $totalInd ? 'success' : 'warning';
                    }),
            ])
            ->defaultSort('nama')
            ->paginated([10])
            ->defaultPaginationPageOption(10);
    }
}
