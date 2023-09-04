<?php

namespace App\Filament\Resources\FamilleResource\Pages;

use App\Filament\Resources\FamilleResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFamilles extends ManageRecords
{
    protected static string $resource = FamilleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
