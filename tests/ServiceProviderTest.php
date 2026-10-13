<?php

declare(strict_types=1);

/*
 * tests/ServiceProviderTest.php
 *
 * The service provider: config, views, translations and the stylesheet.
 */

use Filament\Support\Assets\AssetManager;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Schmeits\FilamentCharacterCounter\FilamentCharacterCounterServiceProvider;

it('registers and boots the service provider', function () {
    expect(app()->getLoadedProviders())
        ->toHaveKey(FilamentCharacterCounterServiceProvider::class);
});

it('keeps the package name and view namespace stable', function () {
    expect(FilamentCharacterCounterServiceProvider::$name)->toBe('filament-character-counter')
        ->and(FilamentCharacterCounterServiceProvider::$viewNamespace)->toBe('filament-character-counter')
        ->and(View::getFinder()->getHints())->toHaveKey('filament-character-counter');
});

it('merges the config into the application', function () {
    expect(config('character-counter.load_css'))->toBeTrue();
});

it('registers the stylesheet with Filament and ships the file', function () {
    $styles = FilamentAsset::getStyles(['schmeits/filament-character-counter']);

    expect(array_map(fn (Css $style): string => $style->getId(), $styles))
        ->toBe(['filament-character-counter-styles']);

    foreach ($styles as $style) {
        expect(file_exists($style->getPath()))->toBeTrue();
    }
});

it('does not register the stylesheet when load_css is off', function () {
    FilamentAsset::swap(new AssetManager);

    config()->set('character-counter.load_css', false);

    (new FilamentCharacterCounterServiceProvider(app()))->packageBooted();

    expect(FilamentAsset::getStyles(['schmeits/filament-character-counter']))->toBe([]);
});

it('registers the package translations', function () {
    expect(Lang::has('filament-character-counter::character-counter.character_label'))->toBeTrue()
        ->and(Lang::has('filament-character-counter::character-counter.character_separator'))->toBeTrue()
        ->and(Lang::has('filament-character-counter::character-counter.characters_over'))->toBeTrue();
});

it('has the same translation keys in every language', function () {
    $reference = array_keys(require __DIR__ . '/../resources/lang/en/character-counter.php');

    foreach (glob(__DIR__ . '/../resources/lang/*', GLOB_ONLYDIR) ?: [] as $language) {
        expect(array_keys(require $language . '/character-counter.php'))->toEqualCanonicalizing(
            $reference,
            'Language ' . basename($language) . ' has different keys than en.',
        );
    }
});

it('keeps the :count placeholder in every over-the-limit message', function () {
    foreach (glob(__DIR__ . '/../resources/lang/*', GLOB_ONLYDIR) ?: [] as $language) {
        $texts = require $language . '/character-counter.php';

        expect($texts['characters_over'])->toContain(':count');
    }
});
