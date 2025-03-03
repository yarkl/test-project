<?php

declare(strict_types=1);

namespace App\Test\Unit\CommissionCalculator;

use PHPUnit\Framework\TestCase;
use Test\CommissionRateCalculator\CommissionCalculator\CommissionCalculator;

final class CommissionCalculatorTest extends TestCase
{
    private CommissionCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new CommissionCalculator(
            ['DE', 'FR', 'IT'],
            0.01,
            0.02
        );
    }

    public function testCalculateWithEuroCurrency()
    {
        $result = $this->calculator->calculate('EUR', 1.0, 100.0, 'DE');
        $this->assertEquals(1.0, $result); // 100 * 0.01
    }

    public function testCalculateWithZeroRate()
    {
        $result = $this->calculator->calculate('USD', 0, 100.0, 'US');
        $this->assertEquals(2.0, $result); // 100 * 0.02
    }

    public function testCalculateWithConversion()
    {
        $result = $this->calculator->calculate('USD', 2.0, 100.0, 'FR');
        $this->assertEquals(0.5, $result); // (100 / 2) * 0.01
    }

    public function testCalculateForNonEuropeanCountry()
    {
        $result = $this->calculator->calculate('GBP', 2.0, 100.0, 'US');
        $this->assertEquals(1.0, $result); // (100 / 2) * 0.02
    }
}
