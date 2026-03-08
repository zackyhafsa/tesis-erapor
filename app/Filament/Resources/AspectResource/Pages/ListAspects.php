<?php

namespace App\Filament\Resources\AspectResource\Pages;

use App\Filament\Resources\AspectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAspects extends ListRecords
{
    protected static string $resource = AspectResource::class;

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
                        \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\AspectsImport, $file);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Berhasil Import Data Aspek')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Gagal Import Data Aspek')
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
                    return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\AspectsTemplateExport, 'Template_Import_Aspek.xlsx');
                }),

            Actions\CreateAction::make(),
        ];
    }
}
