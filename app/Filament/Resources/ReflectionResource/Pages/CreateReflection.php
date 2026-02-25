<?php

namespace App\Filament\Resources\ReflectionResource\Pages;

use App\Filament\Resources\ReflectionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReflection extends CreateRecord
{
    protected static string $resource = ReflectionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
