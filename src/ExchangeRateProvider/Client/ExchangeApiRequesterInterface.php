<?php

namespace Test\CommissionRateCalculator\ExchangeRateProvider\Client;

use Test\CommissionRateCalculator\DTO\BinResultDTO;

interface ExchangeApiRequesterInterface
{
    public function fetch();
}
