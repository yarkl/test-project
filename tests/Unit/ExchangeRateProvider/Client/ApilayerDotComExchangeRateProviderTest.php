<?php

declare(strict_types=1);

namespace App\Tests\Unit\ExchangeRateProvider\Client;

use Exception;
use PHPUnit\Framework\TestCase;
use Test\CommissionRateCalculator\ExchangeRateProvider\Client\ApilayerDotComeExchangeRateProvider;

final class ApilayerDotComExchangeRateProviderTest extends TestCase
{
    private ApilayerDotComeExchangeRateProvider $provider;

    protected function setUp(): void
    {
        $this->provider = $this->getMockBuilder(ApilayerDotComeExchangeRateProvider::class)
            ->onlyMethods(['sendRequest']) // Мокаем только HTTP-запрос
            ->setConstructorArgs(['test-api-key', 'https://api.example.com'])
            ->getMock();
    }

    public function testFetchSuccess()
    {
        $mockResponse = json_encode([
            'success' => true,
            'rates' => [
                'EUR' => 1.0,
                'USD' => 1.1
            ]
        ]);

        $this->provider->method('sendRequest')->willReturn($mockResponse);

        $result = $this->provider->fetch();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('rates', $result);
        $this->assertEquals(1.0, $result['rates']['EUR']);
        $this->assertEquals(1.1, $result['rates']['USD']);
    }

    public function testFetchInvalidJson()
    {
        $this->provider->method('sendRequest')->willReturn('invalid json');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid JSON response from API');

        $this->provider->fetch();
    }

    public function testFetchCurlError()
    {
        $mock = $this->getMockBuilder(ApilayerDotComeExchangeRateProvider::class)
            ->onlyMethods(['sendRequest'])
            ->setConstructorArgs(['test-api-key', 'https://api.example.com'])
            ->getMock();

        $mock->method('sendRequest')->willThrowException(new Exception("cURL Error: Timeout"));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("cURL Error: Timeout");

        $mock->fetch();
    }
}
