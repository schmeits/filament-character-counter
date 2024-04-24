<?php

namespace Schmeits\FilamentCharacterCounter\Forms\Concerns;

use Closure;

trait HasCharacterLimit
{
    protected int | Closure | null $characterLimit = 0;

    public function characterLimit(int | Closure | null $value = null): self
    {
        $this->characterLimit = $value;

        return $this;
    }

    public function getCharacterLimit(): ?int
    {
        if ($this->maxLength) {
            return $this->maxLength;
        }

        return (int) $this->evaluate($this->characterLimit);
    }
}
