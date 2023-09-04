<?php

namespace App\Filament\Resources\SinistreResource\Pages;

use App\Filament\Resources\SinistreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSinistre extends EditRecord
{
    protected static string $resource = SinistreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
