<?php

declare(strict_types=1);

namespace Test\CommissionRateCalculator\CommissionCalculator;

readonly class CommissionCalculator implements CommissionCalculatorInterface
{
    public function __construct(
        private array $europeanCountriesShortCodes,
        private float $europeanCountriesMultiplier,
        private float $otherCountriesMultiplier,
    ) {}

    public function calculate(
        string $currency,
        float $rate,
        float $transactionAmount,
        string $countryShortCode,
    ): float {
        $commission = ($currency === 'EUR' || $rate == 0)
            ? $transactionAmount
            : $transactionAmount / $rate;

        $multiplier = in_array($countryShortCode, $this->europeanCountriesShortCodes, true)
            ? $this->europeanCountriesMultiplier
            : $this->otherCountriesMultiplier;

        return $commission * $multiplier;
    }
}