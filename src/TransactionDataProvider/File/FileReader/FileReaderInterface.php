<?php

namespace Test\CommissionRateCalculator\TransactionDataProvider\File\FileReader;

interface FileReaderInterface
{
    public function readByLine(string $filePath): array;
}
