<?php

namespace App\Filament\Resources\FormuleResource\Pages;

use App\Filament\Resources\FormuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormules extends ListRecords
{
    protected static string $resource = FormuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
