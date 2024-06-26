<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Translation\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Juzaweb\CMS\Contracts\GoogleTranslate;
use Juzaweb\CMS\Contracts\LocalPluginRepositoryContract;
use Juzaweb\CMS\Contracts\LocalThemeRepositoryContract;
use Juzaweb\CMS\Facades\Plugin;
use Juzaweb\Translation\Contracts\TranslationFinder;
use Juzaweb\Translation\Contracts\TranslationManager as TranslationManagerContract;
use Juzaweb\Translation\Models\Translation;
use Juzaweb\Translation\Support\Translations\TranslationExporter;
use Juzaweb\Translation\Support\Translations\TranslationImporter;
use Juzaweb\Translation\Support\Translations\TranslationLocale;
use Juzaweb\Translation\Support\Translations\TranslationTranslate;

class TranslationManager implements TranslationManagerContract
{
    public function __construct(
        protected LocalPluginRepositoryContract $pluginRepository,
        protected LocalThemeRepositoryContract $themeRepository,
        protected TranslationFinder $translationFinder,
        protected GoogleTranslate $googleTranslate
    ) {
    }

    public function export(string $module = 'cms', string $name = null): TranslationExporter
    {
        $module = $this->find($module, $name);

        return $this->createTranslationExporter($module);
    }

    public function import(string $module, string $name = null): TranslationImporter
    {
        $module = $this->find($module, $name);

        return $this->createTranslationImporter($module);
    }

    public function translate(
        string $source,
        string $target,
        string $module = 'cms',
        string $name = 'core'
    ): TranslationTranslate {
        $module = $this->find($module, $name);

        return $this->createTranslationTranslate($module)->setSource($source)->setTarget($target);
    }

    public function locale(string|Collection $module, string $name = null): TranslationLocale
    {
        $module = $this->find($module, $name);

        return $this->createTranslationLocale($module);
    }

    public function find(string|Collection $module, string $name = null): Collection
    {
        if ($module instanceof Collection) {
            return $module;
        }

        switch ($module) {
            case 'plugin':
                $plugin = $this->pluginRepository->find($name);
                throw_if($plugin === null, new \RuntimeException("Plugin {$name} not found"));

                return new Collection(
                    [
                        'key' => $plugin->getSnakeName(),
                        'title' => $plugin->getDisplayName(),
                        'name' => $plugin->get('name'),
                        'namespace' => $plugin->getDomainName(),
                        'type' => 'plugin',
                        'lang_path' => $plugin->getPath('src/resources/lang'),
                        'src_path' => $plugin->getPath('src'),
                        'publish_path' => resource_path("lang/plugins/{$name}"),
                    ]
                );
            case 'theme':
                $theme = $this->themeRepository->find($name);
                if ($theme === null) {
                    throw new \RuntimeException("Theme {$name} not found");
                }

                return new Collection(
                    [
                        'key' => 'theme_' . $theme->get('name'),
                        'title' => $theme->get('title'),
                        'name' => $theme->get('name'),
                        'namespace' => '*',
                        'type' => 'theme',
                        'lang_path' => $theme->getPath('lang'),
                        'src_path' => $theme->getPath('views'),
                        'publish_path' => resource_path("lang/themes/{$name}"),
                    ]
                );
            case 'cms':
                return new Collection(
                    [
                        'key' => 'core',
                        'title' => 'CMS',
                        'namespace' => 'cms',
                        'type' => 'cms',
                        'lang_path' => base_path('vendor/juzaweb/modules/resources/lang'),
                        'src_path' => base_path('vendor/juzaweb/modules/modules'),
                        'publish_path' => resource_path('lang/vendor/cms'),
                    ]
                );
        }

        throw new \RuntimeException('Module not found');
    }

    public function modules(): Collection
    {
        $result['core'] = $this->find('cms');
        $themes = $this->themeRepository->all();
        foreach ($themes as $theme) {
            $info = $this->find('theme', $theme->getName());
            $result[$info->get('key')] = $info;
        }

        $plugins = Plugin::all();
        foreach ($plugins as $plugin) {
            $info = $this->find('plugin', $plugin->get('name'));
            $result[$info->get('key')] = $info;
        }

        return collect($result);
    }

    public function importTranslationLine(array $data, bool $force = false): Translation
    {
        if ($force) {
            return Translation::updateOrCreate(
                Arr::only($data, ['locale', 'group', 'namespace', 'key', 'object_type', 'object_key']),
                Arr::only($data, ['value'])
            );
        }

        return Translation::firstOrCreate(
            Arr::only($data, ['locale', 'group', 'namespace', 'key', 'object_type', 'object_key']),
            Arr::only($data, ['value'])
        );
    }

    protected function createTranslationLocale(Collection $module): TranslationLocale
    {
        return new TranslationLocale($module);
    }

    protected function createTranslationExporter(Collection $module): TranslationExporter
    {
        return new TranslationExporter($module);
    }

    protected function createTranslationTranslate(Collection $module): TranslationTranslate
    {
        return new TranslationTranslate(
            $module,
            $this->googleTranslate,
            $this
        );
    }

    protected function createTranslationImporter(Collection $module): TranslationImporter
    {
        return new TranslationImporter(
            $module,
            $this->translationFinder,
            $this
        );
    }
}
