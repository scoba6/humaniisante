<?php

namespace App\Filament\Resources\MembreResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use App\Filament\Resources\MembreResource;
use App\Models\Membre;
use Filament\Resources\Pages\CreateRecord;

class CreateMembre extends CreateRecord
{
    protected static string $resource = MembreResource::class;


    protected function beforeCreate(): void
    {
        //Le rachat des options est interdit dans la formule 1 (OKOUME)
        if ($this->data['formule_id'] == 1) {
            if ($this->data['optmem'] == 1 || $this->data['denmem'] == 1) {
                Notification::make()
                    ->danger()
                    ->title('OPTIONS NON DISPONIBLES')
                    ->body('La formule choisie ne permet pas de rachat optionnel. Veuillez désactiver les options de rachat')
                    ->color('danger')
                    ->seconds(30)
                    ->send();
                $this->halt();
            }
        }
    }
}
