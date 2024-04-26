<?php

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
