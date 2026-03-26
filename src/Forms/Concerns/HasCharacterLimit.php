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
        $character_limit = (int) $this->evaluate($this->characterLimit);

        if ($this->maxLength && $character_limit === 0) {
            return $this->getMaxLength();
        }

        return $character_limit;
    }
}
