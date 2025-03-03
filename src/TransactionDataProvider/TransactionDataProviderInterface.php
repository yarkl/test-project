<?php

namespace Test\CommissionRateCalculator\TransactionDataProvider;

use Test\CommissionRateCalculator\DTO\TransactionDTO;

interface TransactionDataProviderInterface
{
    /**
     * @return TransactionDTO[]
     */
    public function getData(): array;
}
