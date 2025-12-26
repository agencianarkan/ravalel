<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Cargar helpers de Plaza
        $helperPath = app_path('Helpers/PlazaHelper.php');
        if (file_exists($helperPath)) {
            require_once $helperPath;
        }
    }
}
