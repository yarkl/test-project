<?php

namespace Test\CommissionRateCalculator\CommissionCalculator;

interface CommissionCalculatorInterface
{
    public function calculate(
        string $currency,
        float $rate,
        float $transactionAmount,
        string $countryShortCode,
    ): float;
}
