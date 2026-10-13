<?php

declare(strict_types=1);

/*
 * src/Forms/Components/Textarea.php
 *
 * Filament's Textarea with a character counter.
 */

namespace Schmeits\FilamentCharacterCounter\Forms\Components;

use Schmeits\FilamentCharacterCounter\Forms\Concerns\CanBeShownInsideControl;
use Schmeits\FilamentCharacterCounter\Forms\Concerns\HasCharacterLimit;

class Textarea extends \Filament\Forms\Components\Textarea
{
    use CanBeShownInsideControl;
    use HasCharacterLimit;

    /**
     * @var view-string
     */
    protected string $view = 'filament-character-counter::textarea';
}
