<?php

declare(strict_types=1);

/*
 * src/Forms/Concerns/HasCharacterLimit.php
 *
 * The soft character limit and the counter switch shared by every field of
 * this package. The limit only drives the counter; maxLength() stays the hard
 * limit that the browser and validation enforce.
 */

namespace Schmeits\FilamentCharacterCounter\Forms\Concerns;

use Closure;

trait HasCharacterLimit
{
    protected int | Closure | null $characterLimit = null;

    protected bool | Closure $showCharacterCounter = true;

    public function characterLimit(int | Closure | null $value = null): static
    {
        $this->characterLimit = $value;

        return $this;
    }

    /**
     * The limit shown in the counter. Falls back to maxLength() when no
     * character limit is set, and is 0 when there is no limit at all.
     */
    public function getCharacterLimit(): int
    {
        $characterLimit = $this->evaluate($this->characterLimit);

        if (is_numeric($characterLimit) && (int) $characterLimit > 0) {
            return (int) $characterLimit;
        }

        return max(0, (int) $this->getMaxLength());
    }

    public function showCharacterCounter(bool | Closure $condition = true): static
    {
        $this->showCharacterCounter = $condition;

        return $this;
    }

    public function shouldShowCharacterCounter(): bool
    {
        return (bool) $this->evaluate($this->showCharacterCounter);
    }

    /**
     * @deprecated Use shouldShowCharacterCounter() instead.
     */
    public function getShowCharacterCounter(): bool
    {
        return $this->shouldShowCharacterCounter();
    }

    /**
     * The id of the counter element, used for aria-describedby on the input.
     */
    public function getCharacterCounterId(): string
    {
        return $this->getId() . '-character-counter';
    }

    /**
     * The number of characters in the current state, counted per code point
     * like Laravel's max: rule and the counter in the browser.
     */
    public function getCharacterCount(): int
    {
        $state = $this->getState();

        return is_string($state) || is_numeric($state) ? mb_strlen((string) $state) : 0;
    }
}
