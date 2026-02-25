<?php

namespace App\Filament\Resources\AspectResource\Pages;

use App\Filament\Resources\AspectResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAspect extends CreateRecord
{
    protected static string $resource = AspectResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
