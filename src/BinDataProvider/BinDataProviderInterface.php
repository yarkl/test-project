<?php

namespace Test\CommissionRateCalculator\BinDataProvider;

use Test\CommissionRateCalculator\DTO\BinResultDTO;
use Test\CommissionRateCalculator\DTO\TransactionDTO;

interface BinDataProviderInterface
{
    public function getBinData(TransactionDTO $transactionDTO): BinResultDTO;
}
