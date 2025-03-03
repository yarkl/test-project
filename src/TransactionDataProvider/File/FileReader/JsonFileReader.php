<?php

declare(strict_types=1);

namespace Test\CommissionRateCalculator\TransactionDataProvider\File\FileReader;

readonly class JsonFileReader implements FileReaderInterface
{
    public function readByLine(string $filePath): array
    {
        $file = fopen($filePath, "r");

        $data = [];
        while (($line = fgets($file)) !== false) {
            if (json_validate($line)) {
                $data[] = json_decode(trim($line), true);
            }
        }
        fclose($file);

        return $data;
    }
}
