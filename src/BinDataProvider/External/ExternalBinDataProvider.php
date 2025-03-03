<?php

declare(strict_types=1);

namespace Test\CommissionRateCalculator\BinDataProvider\External;

use Test\CommissionRateCalculator\DTO\BinResultDTO;
use Test\CommissionRateCalculator\DTO\TransactionDTO;
use Test\CommissionRateCalculator\BinDataProvider\BinDataProviderInterface;
use Test\CommissionRateCalculator\BinDataProvider\External\Client\BinApiRequesterInterface;

readonly class ExternalBinDataProvider implements BinDataProviderInterface
{
    public function __construct(private readonly BinApiRequesterInterface $binApiRequester)
    {

    }

    public function getBinData(TransactionDTO $transactionDTO): BinResultDTO
    {
        return $this->binApiRequester->fetch($transactionDTO->bin);
    }
}
