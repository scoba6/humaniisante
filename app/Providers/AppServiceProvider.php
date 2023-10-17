<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;
use Filament\Navigation\NavigationGroup;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('ADHESIONS')
                    ->collapsed(true),
                NavigationGroup::make()
                    ->label('SINISTRES')
                    ->collapsed(true),
                NavigationGroup::make()
                    ->label('PARAMETRES')
                    ->collapsed(true),
                NavigationGroup::make()
                    ->label('ADMINISTRATION')
                    ->collapsed(true),

            ]);
        });
    }
}
