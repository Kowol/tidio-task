<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class SimpleTest extends TestCase
{
    public function testItChecksValue(): void
    {
        self::assertEquals(1, 1);
    }
}
