<?php

namespace App\Providers;

use App\Http\ViewComposers\SettingComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            'auth.*',
            SettingComposer::class
        );
        View::composer(
            'admin.*',
            SettingComposer::class
        );
        View::composer(
            'mails.*',
            SettingComposer::class
        );
    }
}
