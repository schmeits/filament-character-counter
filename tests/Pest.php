<?php

declare(strict_types=1);

/*
 * tests/Pest.php
 *
 * Binds every test to the package TestCase.
 */

use Schmeits\FilamentCharacterCounter\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);
