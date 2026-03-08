<?php

namespace App\Filament\Resources\ReflectionResource\Pages;

use App\Filament\Resources\ReflectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReflections extends ListRecords
{
    protected static string $resource = ReflectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('importExcelMurni')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\FileUpload::make('file')
                        ->label('Pilih File Excel (.xlsx / .xls)')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $file = public_path('storage/' . $data['file']);
                    
                    try {
                        \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\ReflectionsImport, $file);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Berhasil Import Data Refleksi')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Gagal Import Data Refleksi')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            \Filament\Actions\Action::make('downloadTemplate')
                ->label('Download Template Excel')
                ->icon('heroicon-o-document-arrow-down')
                ->color('gray')
                ->action(function () {
                    return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ReflectionsTemplateExport, 'Template_Import_Refleksi.xlsx');
                }),

            Actions\CreateAction::make(),
        ];
    }
}
