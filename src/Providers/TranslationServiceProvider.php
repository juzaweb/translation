<?php

namespace Juzaweb\Translation\Providers;

use Juzaweb\CMS\Contracts\GoogleTranslate as GoogleTranslateContract;
use Juzaweb\CMS\Contracts\LocalPluginRepositoryContract;
use Juzaweb\CMS\Contracts\LocalThemeRepositoryContract;
use Juzaweb\CMS\Facades\ActionRegister;
use Juzaweb\CMS\Support\ServiceProvider;
use Juzaweb\Translation\Commands;
use Juzaweb\Translation\Contracts\TranslationContract;
use Juzaweb\Translation\Contracts\TranslationFinder as TranslationFinderContract;
use Juzaweb\Translation\Contracts\TranslationManager as TranslationManagerContract;
use Juzaweb\Translation\Models\LanguageLine;
use Juzaweb\Translation\Support\Locale;
use Juzaweb\Translation\Support\TranslationManager;
use Juzaweb\Translation\Support\Translations\TranslationFinder;
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
        $this->app['config']->set('translation-loader.model', LanguageLine::class);

        $this->app->register(RouteServiceProvider::class);

        $this->app->singleton(
            TranslationContract::class,
            function () {
                return new Locale();
            }
        );

        $this->app->singleton(
            TranslationFinderContract::class,
            function ($app) {
                return new TranslationFinder();
            }
        );

        $this->app->singleton(
            TranslationManagerContract::class,
            function ($app) {
                return new TranslationManager(
                    $app[LocalPluginRepositoryContract::class],
                    $app[LocalThemeRepositoryContract::class],
                    $app[TranslationFinderContract::class],
                    $app[GoogleTranslateContract::class]
                );
            }
        );
    }
}
