<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\Translation\Commands;

use Juzaweb\CMS\Contracts\TranslationManager;
use Symfony\Component\Console\Input\InputOption;

class TranslateCMSFromEnglishCommand extends TranslationCommand
{
    protected $name = 'cms:translate-from-en';

    protected string $source = 'en';

    private array $targetDefaults = ['vi', 'fr', 'tr', 'zh', 'ru', 'ko', 'ja'];

    public function handle(): int
    {
        foreach ($this->getTargetLanguages() as $language) {
            $this->info("Translate from {$this->source} to {$language}");
            $this->executeTranslation($language);

            $this->newLine();

            $this->info("Export {$language} language");
            $this->exportTranslation($language);
        }

        return self::SUCCESS;
    }

    protected function exportTranslation(string $language): void
    {
        $exporter = app(TranslationManager::class)->export();
        $exporter->setLanguage($language);
        $exporter->run();
    }

    protected function executeTranslation(string $language): void
    {
        $translate = app(TranslationManager::class)->translate($this->source, $language);

        $bar = $this->output->createProgressBar($translate->getTranslationLines()->count());
        $bar->start();

        $translate->progressCallback(
            function ($model) use ($bar) {
                $bar->advance();
            }
        );

        $translate->run();

        $bar->finish();
    }

    protected function getTargetLanguages(): array
    {
        if ($target = $this->option('target')) {
            return explode(',', $target);
        }

        return $this->targetDefaults;
    }

    protected function getOptions(): array
    {
        return [
            ['target', 't', InputOption::VALUE_NONE, 'Target languages translate.', null],
        ];
    }
}
