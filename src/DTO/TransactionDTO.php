<?php

declare(strict_types=1);

namespace Test\CommissionRateCalculator\DTO;

class TransactionDTO
{
    public function __construct(
        public string $bin,
        public float $amount,
        public string $currency
    ) {}
}
