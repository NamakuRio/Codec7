<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Force https if app debug false
        if (config('app.debug') != true) {
            URL::forceScheme('https');
        }

        // Default string length 191 character
        Schema::defaultStringLength(191);

        // Blade Directive Asset
        Blade::directive('asset', function ($asset) {
            return "<?= asset($asset) ?>";
        });

        // Blade Directive Trans
        Blade::directive('trans', function ($trans) {
            return "<?= trans($trans) ?>";
        });

        // Blade Directive Route
        Blade::directive('route', function ($route) {
            return "<?= route($route) ?>";
        });
    }
}
