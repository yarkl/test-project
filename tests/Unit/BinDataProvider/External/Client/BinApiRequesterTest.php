<?php

declare(strict_types=1);

namespace App\Tests\Unit\BinDataProvider\External\Client;

use PHPUnit\Framework\TestCase;
use Test\CommissionRateCalculator\DTO\BinResultDTO;
use Test\CommissionRateCalculator\BinDataProvider\External\Client\BinApiRequester;

final class BinApiRequesterTest extends TestCase
{
    public function testFetch(): void
    {
        $binApiUrl = 'https://example.com';
        $firstFourDigits = '4571';

        $mockResponse = [
            'scheme' => 'visa',
            'brand' => 'VISA',
            'country' => [
                'numeric' => '840',
                'alpha2' => 'US',
                'name' => 'United States',
                'currency' => 'USD'
            ]
        ];

        $binApiRequester = $this->getMockBuilder(BinApiRequester::class)
            ->onlyMethods(['sendRequest'])
            ->setConstructorArgs(["{$binApiUrl}/{$firstFourDigits}"])
            ->getMock();

        $binApiRequester->method('sendRequest')->willReturn($mockResponse);

        $result = $binApiRequester->fetch($firstFourDigits);

        $this->assertInstanceOf(BinResultDTO::class, $result);
        $this->assertEquals('visa', $result->scheme);
        $this->assertEquals('VISA', $result->brand);
        $this->assertEquals('US', $result->countrySortCode);
        $this->assertEquals('United States', $result->countryName);
        $this->assertEquals('USD', $result->currency);
    }
}
