<?php

namespace App\Filament\Resources\MembreResource\Pages;

use App\Filament\Resources\MembreResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewQrCode extends ViewRecord
{
    protected static string $resource = MembreResource::class;
    protected static string $view = 'filament.resources.MembreResource.pages.ViewRecord';

}
