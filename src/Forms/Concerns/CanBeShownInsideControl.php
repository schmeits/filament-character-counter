<?php

declare(strict_types=1);

/*
 * src/Forms/Concerns/CanBeShownInsideControl.php
 *
 * Lets a field render its counter inside the input wrapper instead of below
 * it. Supported by TextInput and Textarea.
 */

namespace Schmeits\FilamentCharacterCounter\Forms\Concerns;

use Closure;

trait CanBeShownInsideControl
{
    protected bool | Closure $isShownInsideControl = false;

    public function showInsideControl(bool | Closure $condition = true): static
    {
        $this->isShownInsideControl = $condition;

        return $this;
    }

    public function isShownInsideControl(): bool
    {
        return (bool) $this->evaluate($this->isShownInsideControl);
    }
}
