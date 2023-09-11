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

    public function getTabs(): array
    {
        return [
            null => ListRecords\Tab::make('Tous'),
            'A vérifier' => ListRecords\Tab::make()->query(fn ($query) => $query->where('status', '1')),
            'Vérifiés' => ListRecords\Tab::make()->query(fn ($query) => $query->where('status', '2')),
            'Réglés' => ListRecords\Tab::make()->query(fn ($query) => $query->where('status', '3')),
        ];
    }
}
