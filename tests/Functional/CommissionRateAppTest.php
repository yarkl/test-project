<?php

declare(strict_types=1);

namespace App\Test\Functional;

use PHPUnit\Framework\TestCase;
use Test\CommissionRateCalculator\DTO\BinResultDTO;
use Test\CommissionRateCalculator\CommissionRateApp;
use Test\CommissionRateCalculator\DTO\TransactionDTO;
use Test\CommissionRateCalculator\BinDataProvider\BinDataProviderInterface;
use Test\CommissionRateCalculator\CommissionCalculator\CommissionCalculatorInterface;
use Test\CommissionRateCalculator\ExchangeRateProvider\Client\ExchangeApiRequesterInterface;
use Test\CommissionRateCalculator\TransactionDataProvider\TransactionDataProviderInterface;

class CommissionRateAppTest extends TestCase
{
    private CommissionRateApp $app;
    private TransactionDataProviderInterface $transactionProviderMock;
    private ExchangeApiRequesterInterface $exchangeApiMock;
    private CommissionCalculatorInterface $commissionCalculatorMock;
    private BinDataProviderInterface $binProviderMock;

    protected function setUp(): void
    {
        $this->transactionProviderMock = $this->createMock(TransactionDataProviderInterface::class);
        $this->exchangeApiMock = $this->createMock(ExchangeApiRequesterInterface::class);
        $this->commissionCalculatorMock = $this->createMock(CommissionCalculatorInterface::class);
        $this->binProviderMock = $this->createMock(BinDataProviderInterface::class);

        $this->app = new CommissionRateApp(
            $this->transactionProviderMock,
            $this->exchangeApiMock,
            $this->commissionCalculatorMock,
            $this->binProviderMock
        );
    }

    public function testCorrectCommissions()
    {
        $testTransaction = new TransactionDTO('45717360', 100.0, 'EUR');
        $testExchangeData = ['rates' => ['EUR' => 1.0, 'USD' => 1.1]];
        $testBinData = new BinResultDTO(
            'visa',
            'debit',
            '250',
            'FR',
            'France',
            'EUR'
        );

        $this->transactionProviderMock->method('getData')->willReturn([$testTransaction]);
        $this->exchangeApiMock->method('fetch')->willReturn($testExchangeData);
        $this->binProviderMock->method('getBinData')->willReturn($testBinData);
        $this->commissionCalculatorMock
            ->method('calculate')
            ->with('EUR', 1.0, 100.0, 'FR')
            ->willReturn(1.0);

        $commissions = $this->app->run();

        $this->assertCount(1, $commissions);
        $this->assertEquals(1.0, $commissions[0]);
    }

    public function testRunHandlesMultipleTransactions()
    {
        $transactions = [
            new TransactionDTO('45717360', 100.0, 'EUR'),
            new TransactionDTO('516793', 200.0, 'USD')
        ];

        $exchangeData = ['rates' => ['EUR' => 1.0, 'USD' => 1.2]];
        $binData1 = new BinResultDTO(
            'visa',
            'debit',
            '250',
            'FR',
            'France',
            'EUR'
        );
        $binData2 = new BinResultDTO(
            'mastercard',
            'credit',
            '840',
            'US',
            'United States',
            'USD'
        );

        $this->transactionProviderMock->method('getData')->willReturn($transactions);
        $this->exchangeApiMock->method('fetch')->willReturn($exchangeData);
        $this->binProviderMock->method('getBinData')->willReturnOnConsecutiveCalls($binData1, $binData2);
        $this->commissionCalculatorMock
            ->method('calculate')
            ->willReturnOnConsecutiveCalls(1.0, 3.33);

        $commissions = $this->app->run();

        $this->assertCount(2, $commissions);
        $this->assertEquals(1.0, $commissions[0]);
        $this->assertEquals(3.33, $commissions[1]);
    }
}
