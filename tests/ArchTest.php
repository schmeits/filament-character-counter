<?php

declare(strict_types=1);

/*
 * tests/ArchTest.php
 *
 * Architecture rules for the package source.
 */

arch('does not use debugging functions')
    ->expect(['dd', 'dump', 'ray', 'var_dump'])
    ->each->not->toBeUsed();

arch('uses strict types everywhere')
    ->expect('Schmeits\FilamentCharacterCounter')
    ->toUseStrictTypes();
