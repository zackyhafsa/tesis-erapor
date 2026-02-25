<?php

namespace App\Filament\Resources\ReflectionResource\Pages;

use App\Filament\Resources\ReflectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReflection extends EditRecord
{
    protected static string $resource = ReflectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
