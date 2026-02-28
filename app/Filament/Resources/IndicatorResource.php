<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IndicatorResource\Pages;
use App\Models\Indicator;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
                // 1. Menampilkan Jenis Penilaian (Dari tabel relasi Aspek)
                Tables\Columns\TextColumn::make('aspect.jenis_penilaian')
                    ->label('Jenis Penilaian')
                    ->badge()
                    ->sortable(),

                // 2. Menampilkan Nama Aspek
                Tables\Columns\TextColumn::make('aspect.nama_aspek')
                    ->label('Aspek Penilaian')
                    ->sortable()
                    ->searchable(),

                // 3. Menampilkan Nama Indikator
                Tables\Columns\TextColumn::make('nama_indikator')
                    ->label('Indikator')
                    ->limit(30)
                    ->searchable(),

                // 4. MEMUNCULKAN KOLOM BARU: Deskripsi Kriteria
                Tables\Columns\TextColumn::make('deskripsi_kriteria')
                    ->label('Deskripsi Kriteria')
                    ->limit(40) // Dibatasi 40 huruf agar tabel tidak membludak
                    ->searchable(),
            ])

            ->headerActions([
                // 1. TOMBOL EXPORT KE EXCEL (Bawaan Excel - Biarkan karena udah rapi kalau di Excel)
                \pxlrbt\FilamentExcel\Actions\Tables\ExportAction::make('eksport_excel')
                    ->label('Eksport Data (Excel)')
                    ->color('success')
                    ->icon('heroicon-o-table-cells')
                    ->exports([
                        \pxlrbt\FilamentExcel\Exports\ExcelExport::make('excel')
                            ->withFilename('Data_Indikator_'.date('Y-m-d'))
                            ->withColumns([
                                \pxlrbt\FilamentExcel\Columns\Column::make('aspect.jenis_penilaian')->heading('Jenis Penilaian'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('aspect.nama_aspek')->heading('Aspek Penilaian'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('nama_indikator')->heading('Indikator'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('deskripsi_kriteria')->heading('Deskripsi Kriteria'),
                            ]),
                    ]),

                // 2. TOMBOL EXPORT KE PDF (KUSTOM HTML SUPER RAPI)
                Tables\Actions\Action::make('eksport_pdf_kustom')
                    ->label('Eksport Data (PDF)')
                    ->color('danger')
                    ->icon('heroicon-o-document-text')
                    // Membaca Livewire untuk mengambil data yang sedang di-filter di layar
                    ->action(function ($livewire) {
                        // Ambil semua data indikator beserta aspeknya yang sedang di-filter
                        $indikators = $livewire->getFilteredTableQuery()->with('aspect')->get();

                        // Load ke desain kertas HTML kita tadi
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cetak.indikator', [
                            'data' => $indikators,
                        ]);

                        // Langsung download file PDF-nya
                        return response()->streamDownload(fn () => print ($pdf->output()), 'Data_Indikator_'.date('Y-m-d').'.pdf');
                    }),

                // Tables\Actions\ImportAction::make()
                //     ->importer(\App\Filament\Imports\IndicatorImporter::class)
                //     ->label('Import Indikator dari CSV')
                //     ->color('primary')
                //     ->icon('heroicon-o-arrow-up-tray'),
            ])

            ->filters([
                // FITUR FILTER: Tombol corong di kanan atas tabel
                Tables\Filters\SelectFilter::make('jenis_penilaian')
                    ->label('Filter Jenis Penilaian')
                    ->options([
                        'Kinerja' => 'Kinerja',
                        'Proyek' => 'Proyek',
                    ])
                    // Logika untuk mencari jenis penilaian di tabel sebelah (Aspek)
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        if (! empty($data['value'])) {
                            $query->whereHas('aspect', function (\Illuminate\Database\Eloquent\Builder $query) use ($data) {
                                $query->where('jenis_penilaian', $data['value']);
                            });
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    // TOMBOL EKSPORT PINDAH KE SINI
                    \pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction::make('eksport')
                        ->label('Eksport Data Terpilih')
                        ->color('success')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->exports([
                            // PILIHAN 1: EXCEL
                            \pxlrbt\FilamentExcel\Exports\ExcelExport::make('excel')
                                ->label('Eksport ke Excel')
                                ->withFilename('Format_Penilaian_'.date('Y-m-d')) // Nama file excel
                                ->withColumns([
                                    \pxlrbt\FilamentExcel\Columns\Column::make('aspect.jenis_penilaian')->heading('Jenis Penilaian'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('aspect.nama_aspek')->heading('Aspek Penilaian'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('nama_indikator')->heading('Indikator'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('deskripsi_kriteria')->heading('Deskripsi Kriteria'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('catatan_skor_4')->heading('Rubrik Skor 4 (Sangat Baik)'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('catatan_skor_3')->heading('Rubrik Skor 3 (Baik)'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('catatan_skor_2')->heading('Rubrik Skor 2 (Cukup)'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('catatan_skor_1')->heading('Rubrik Skor 1 (Kurang)'),
                                ]),

                            // PILIHAN 2: PDF
                            \pxlrbt\FilamentExcel\Exports\ExcelExport::make('pdf')
                                ->label('Eksport ke PDF')
                                ->withFilename('Format_Penilaian_'.date('Y-m-d').'.pdf') // Tambahkan .pdf di sini
                                ->withWriterType(\Maatwebsite\Excel\Excel::DOMPDF)
                                ->withColumns([
                                    \pxlrbt\FilamentExcel\Columns\Column::make('aspect.jenis_penilaian')->heading('Jenis Penilaian'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('aspect.nama_aspek')->heading('Aspek Penilaian'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('nama_indikator')->heading('Indikator'),
                                    \pxlrbt\FilamentExcel\Columns\Column::make('deskripsi_kriteria')->heading('Deskripsi Kriteria'),
                                ]),
                        ]),
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
