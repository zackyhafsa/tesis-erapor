<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Imports\StudentImporter;
use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->importer(StudentImporter::class)
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success'),
            Actions\CreateAction::make(),
        ];
    }
}
