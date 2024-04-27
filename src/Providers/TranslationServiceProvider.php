<?php

namespace Juzaweb\Translation\Providers;

use Juzaweb\CMS\Facades\ActionRegister;
use Juzaweb\CMS\Support\ServiceProvider;
use Juzaweb\Translation\Commands;
use Juzaweb\Translation\Contracts\TranslationContract;
use Juzaweb\Translation\Support\Locale;
use Juzaweb\Translation\TranslationAction;

class TranslationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        ActionRegister::register(TranslationAction::class);

        $this->commands(
            [
                Commands\ImportTranslationCommand::class,
                Commands\TranslateCMSFromEnglishCommand::class,
            ]
        );
    }

    public function register(): void
    {
        $this->app['config']->set('translation-loader.model', \Juzaweb\Translation\Models\LanguageLine::class);

        $this->app->register(RouteServiceProvider::class);

        $this->app->singleton(
            TranslationContract::class,
            function () {
                return new Locale();
            }
        );
    }
}
