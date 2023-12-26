<?php

namespace App\Filament\Resources\ActeResource\Pages;

use App\Filament\Resources\ActeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageActes extends ManageRecords
{
    protected static string $resource = ActeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
