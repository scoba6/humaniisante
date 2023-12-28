<?php

namespace App\Filament\Resources\PChargeResource\Pages;

use App\Filament\Resources\PChargeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPCharges extends ListRecords
{
    protected static string $resource = PChargeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
