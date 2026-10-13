<?php

declare(strict_types=1);

/*
 * src/Forms/Components/TextInput.php
 *
 * Filament's TextInput with a character counter.
 */

namespace Schmeits\FilamentCharacterCounter\Forms\Components;

use Schmeits\FilamentCharacterCounter\Forms\Concerns\CanBeShownInsideControl;
use Schmeits\FilamentCharacterCounter\Forms\Concerns\HasCharacterLimit;

class TextInput extends \Filament\Forms\Components\TextInput
{
    use CanBeShownInsideControl;
    use HasCharacterLimit;

    /**
     * @var view-string
     */
    protected string $view = 'filament-character-counter::text-input';
}
