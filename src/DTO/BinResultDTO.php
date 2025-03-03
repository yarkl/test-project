<?php

declare(strict_types=1);

namespace Test\CommissionRateCalculator\DTO;

class BinResultDTO
{
    public function __construct(
        public string $scheme,
        public string $brand,
        public string $countryCode,
        public string $countrySortCode,
        public string $countryName,
        public string $currency,
    ) {}
}
