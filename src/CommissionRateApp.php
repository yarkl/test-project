<?php

declare(strict_types=1);

namespace Test\CommissionRateCalculator;

use Test\CommissionRateCalculator\BinDataProvider\BinDataProviderInterface;
use Test\CommissionRateCalculator\CommissionCalculator\CommissionCalculatorInterface;
use Test\CommissionRateCalculator\ExchangeRateProvider\Client\ExchangeApiRequesterInterface;
use Test\CommissionRateCalculator\TransactionDataProvider\TransactionDataProviderInterface;

readonly class CommissionRateApp
{
    public function __construct(
        private TransactionDataProviderInterface $transactionDataProvider,
        private ExchangeApiRequesterInterface $exchangeApiRequester,
        private CommissionCalculatorInterface $commissionCalculator,
        private BinDataProviderInterface $binDataProvider,
    ) {}

    public function run(): array
    {
        $transactionData = $this->transactionDataProvider->getData();

        $commissions = [];
        foreach ($transactionData as $transaction) {
            $exchangeData = $this->exchangeApiRequester->fetch();

            $binData = $this->binDataProvider->getBinData($transaction);

            $commissions[] = $this->commissionCalculator->calculate(
                $transaction->currency,
                $exchangeData['rates'][$transaction->currency],
                $transaction->amount,
                $binData->countrySortCode
            );
        }

        return $commissions;
    }
}
