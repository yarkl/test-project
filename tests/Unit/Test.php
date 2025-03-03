<?php

declare(strict_types=1);

namespace App\Test\Unit;

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function testOneEqOne(): void
    {
        $this->assertEquals(1, 1);
    }
}