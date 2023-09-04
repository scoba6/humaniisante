<?php

namespace App\Filament\Resources\PrestataireResource\Pages;

use App\Filament\Resources\PrestataireResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePrestataires extends ManageRecords
{
    protected static string $resource = PrestataireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
