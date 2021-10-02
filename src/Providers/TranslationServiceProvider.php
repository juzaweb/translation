<?php

namespace Juzaweb\Translation\Providers;

use Juzaweb\Support\ServiceProvider;
use Juzaweb\Translation\Actions\MainAction;
use Juzaweb\Translation\Contracts\TranslationContract;
use Juzaweb\Translation\Support\Locale;

class TranslationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerAction([
            MainAction::class
        ]);
    }

    public function register()
    {
        $this->app->singleton(TranslationContract::class, function () {
            return new Locale();
        });
    }
}
