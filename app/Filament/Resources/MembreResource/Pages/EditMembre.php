<?php

namespace App\Filament\Resources\MembreResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\MembreResource;

class EditMembre extends EditRecord
{
    protected static string $resource = MembreResource::class;

    protected function beforeSave(): void
    {
        //Le rachat des options est interdit dans la formule 1 (OKOUME)
        if ($this->data['formule_id'] == 1) {
            if($this->data['optmem'] == 1 || $this->data['denmem'] == 1 ){
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
