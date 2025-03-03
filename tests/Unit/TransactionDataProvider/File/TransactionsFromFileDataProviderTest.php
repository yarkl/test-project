<?php

declare(strict_types=1);

namespace App\Test\TransactionDataProvider\File;

use PHPUnit\Framework\TestCase;
use Test\CommissionRateCalculator\DTO\TransactionDTO;
use Test\CommissionRateCalculator\TransactionDataProvider\File\FileReader\FileReaderInterface;
use Test\CommissionRateCalculator\TransactionDataProvider\File\TransactionsFromFileDataProvider;

final class TransactionsFromFileDataProviderTest extends TestCase
{
    private TransactionsFromFileDataProvider $provider;
    private FileReaderInterface $fileReaderMock;

    protected function setUp(): void
    {
        $this->fileReaderMock = $this->createMock(FileReaderInterface::class);

        $this->provider = new TransactionsFromFileDataProvider(
            __DIR__ . '/test_files',
            $this->fileReaderMock
        );
    }

    public function testGetDataReturnsArrayOfTransactions()
    {
        $testFilePath = __DIR__ . '/test_files/test1.txt';

        $this->fileReaderMock->method('readByLine')->willReturn([
            ['bin' => '45717360', 'amount' => '100.00', 'currency' => 'EUR'],
            ['bin' => '516793', 'amount' => '50.00', 'currency' => 'USD']
        ]);

        if (!is_dir(__DIR__ . '/test_files')) {
            mkdir(__DIR__ . '/test_files', 0777, true);
        }
        file_put_contents($testFilePath, '');

        $transactions = $this->provider->getData();

        $this->assertCount(2, $transactions);
        $this->assertInstanceOf(TransactionDTO::class, $transactions[0]);
        $this->assertEquals('45717360', $transactions[0]->bin);
        $this->assertEquals(100.00, $transactions[0]->amount);
        $this->assertEquals('EUR', $transactions[0]->currency);

        unlink($testFilePath);
        rmdir(__DIR__ . '/test_files');
    }
}
