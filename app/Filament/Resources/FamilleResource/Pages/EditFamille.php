<?php

namespace App\Filament\Resources\FamilleResource\Pages;

use App\Filament\Resources\FamilleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFamille extends EditRecord
{
    protected static string $resource = FamilleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
