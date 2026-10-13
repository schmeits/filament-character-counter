<?php

declare(strict_types=1);

/*
 * tests/TestCase.php
 *
 * Base test case: boots Filament, Livewire and this package in Testbench.
 */

namespace Schmeits\FilamentCharacterCounter\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Schmeits\FilamentCharacterCounter\FilamentCharacterCounterServiceProvider;

class TestCase extends Orchestra
{
    /**
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        // NOTE: the order here is functional, not cosmetic.
        // Filament\Support rebinds Livewire's DataStore to its own
        // DataStoreOverride with a plain bind(). If Livewire is registered
        // first, that bind overwrites the singleton instance and every resolve
        // returns a fresh DataStore. As a result Livewire components hold no
        // state and rendering crashes on an empty errorBag.
        // In a real app filament/support sorts alphabetically before
        // livewire/livewire in discovery, so it works there. Here we have to
        // reproduce that order explicitly.
        return [
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            SupportServiceProvider::class,
            ActionsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            NotificationsServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            LivewireServiceProvider::class,
            FilamentCharacterCounterServiceProvider::class,
        ];
    }
}
