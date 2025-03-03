<?php

namespace Test\CommissionRateCalculator\BinDataProvider\External\Client;

interface BinApiRequesterInterface
{
    public function fetch(string $firstFourDigits);
}
