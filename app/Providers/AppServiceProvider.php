<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Spatie\Health\Facades\Health;
use Illuminate\Support\ServiceProvider;
use Filament\Navigation\NavigationGroup;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\DatabaseSizeCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Checks\Checks\DatabaseTableSizeCheck;
use Spatie\Health\Checks\Checks\DatabaseConnectionCountCheck;

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

        Health::checks([
            OptimizedAppCheck::new(),
            DebugModeCheck::new(),
            EnvironmentCheck::new(),
            DatabaseCheck::new(),
            DatabaseConnectionCountCheck::new()
                ->warnWhenMoreConnectionsThan(50)
                ->failWhenMoreConnectionsThan(100),
            DatabaseSizeCheck::new()
                ->failWhenSizeAboveGb(errorThresholdGb: 5.0),
            DatabaseTableSizeCheck::new()
                ->table('productions', maxSizeInMb: 1_000)
                ->table('prostocks', maxSizeInMb: 2_000),
            PingCheck::new()->url('https://example.com')->timeout(2),

            UsedDiskSpaceCheck::new()
                ->warnWhenUsedSpaceIsAbovePercentage(70)
                ->failWhenUsedSpaceIsAbovePercentage(90),
        ]);
    }
}
