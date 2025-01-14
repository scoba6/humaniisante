<?php

namespace App\Filament\Resources\LocaliteResource\Pages;

use App\Filament\Resources\LocaliteResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLocalites extends ManageRecords
{
    protected static string $resource = LocaliteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
