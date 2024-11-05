<?php

namespace App\Filament\Resources\ReglementResource\Pages;

use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ReglementResource;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use App\Models\Reglement;

class CreateReglement extends CreateRecord
{
    use HasWizard;
    protected static string $resource = ReglementResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('DETAILS DU REGLEMENT')
                ->schema([
                    Section::make()->schema(ReglementResource::getDetailsFormSchema())->columns(),
                ]),

            Step::make('SINISTRES DU REGLEMENT')
                ->schema([
                    Section::make()->schema([
                        ReglementResource::getItemsRepeater(),
                    ]),
                ]),
        ];
    }


}
