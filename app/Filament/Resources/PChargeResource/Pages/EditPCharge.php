<?php

namespace App\Filament\Resources\PChargeResource\Pages;

use App\Filament\Resources\PChargeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPCharge extends EditRecord
{
    protected static string $resource = PChargeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\Action::make('PC')->button()->label('IMPR. PC')
                ->url(fn () => route('printpc', $this->record))
                ->openUrlInNewTab(),
        ];
    }
}
