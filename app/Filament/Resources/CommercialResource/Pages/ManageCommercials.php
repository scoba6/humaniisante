<?php

namespace App\Filament\Resources\CommercialResource\Pages;

use App\Filament\Resources\CommercialResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCommercials extends ManageRecords
{
    protected static string $resource = CommercialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
