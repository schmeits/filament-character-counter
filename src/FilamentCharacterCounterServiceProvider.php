<?php

declare(strict_types=1);

/*
 * src/FilamentCharacterCounterServiceProvider.php
 *
 * Registers the config, views, translations and stylesheet of the package.
 */

namespace Schmeits\FilamentCharacterCounter;

use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentCharacterCounterServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-character-counter';

    public static string $viewNamespace = 'filament-character-counter';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('schmeits/filament-character-counter');
            });

        // The config file is named 'character-counter', not the package's
        // shortName. shortName() only strips the 'laravel-' prefix, so for
        // 'filament-character-counter' it returned that name unchanged. The old
        // check therefore looked for config/filament-character-counter.php, a
        // file that never existed. As a result the config was never merged and
        // the install command could not publish it.
        $configFileName = 'character-counter';

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile($configFileName);
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageBooted(): void
    {
        // Asset Registration
        if (config('character-counter.load_css', true)) {
            FilamentAsset::register(
                $this->getAssets(),
                $this->getAssetPackageName()
            );
        }
    }

    protected function getAssetPackageName(): string
    {
        return 'schmeits/filament-character-counter';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            Css::make('filament-character-counter-styles', __DIR__ . '/../resources/dist/filament-character-counter.css'),
        ];
    }
}
