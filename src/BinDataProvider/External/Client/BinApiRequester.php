<?php

namespace Test\CommissionRateCalculator\BinDataProvider\External\Client;

use PHPUnit\Framework\Exception;
use Test\CommissionRateCalculator\DTO\BinResultDTO;

readonly class BinApiRequester implements BinApiRequesterInterface
{
    public function __construct(private string $binApiUrl)
    {}

    public function fetch(string $firstFourDigits): BinResultDTO
    {
        $binResults = $this->sendRequest("{$this->binApiUrl}/{$firstFourDigits}");

        return new BinResultDTO(
            $binResults['scheme'],
            $binResults['brand'],
            $binResults['country']['numeric'],
            $binResults['country']['alpha2'],
            $binResults['country']['name'],
            $binResults['country']['currency'],
        );
    }

    public function sendRequest(string $url): array
    {
        $binResults = file_get_contents($url);

        if (!json_validate($binResults)) {
            throw new Exception('Bin request failed!');
        }

        return json_decode($binResults, true);
    }
}
