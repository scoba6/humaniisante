<?php

namespace App\Filament\Resources\SinistreResource\Pages;

use App\Filament\Resources\SinistreResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSinistres extends ListRecords
{
    protected static string $resource = SinistreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
