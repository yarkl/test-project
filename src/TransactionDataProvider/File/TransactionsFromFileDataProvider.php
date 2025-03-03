<?php

declare(strict_types=1);

namespace Test\CommissionRateCalculator\TransactionDataProvider\File;

use Test\CommissionRateCalculator\TransactionDataProvider\File\FileReader\FileReaderInterface;
use Test\CommissionRateCalculator\TransactionDataProvider\TransactionDataProviderInterface;
use Test\CommissionRateCalculator\DTO\TransactionDTO;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

readonly class TransactionsFromFileDataProvider implements TransactionDataProviderInterface
{
    public function __construct(
        private string $filesDirectory,
        private FileReaderInterface $fileReader
    ) {}

    /**
     * @return TransactionDTO[]
     */
    public function getData(): array
    {
        $transactionData = [];

        $iterator = new RecursiveDirectoryIterator($this->filesDirectory);

        $recursiveIterator = new RecursiveIteratorIterator($iterator);

        foreach ($recursiveIterator as $fileInfo) {
            if ($fileInfo->isFile()) {
                foreach ($this->fileReader->readByLine($fileInfo->getRealPath()) as $data) {
                    $transactionData[] = new TransactionDTO($data['bin'], floatval($data['amount']), $data['currency']);
                }
            }
        }

        return $transactionData;
    }
}
