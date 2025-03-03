<?php

use Psr\Container\ContainerInterface;
use Test\CommissionRateCalculator\CommissionRateApp;
use Test\CommissionRateCalculator\CommissionCalculator\CommissionCalculator;
use Test\CommissionRateCalculator\BinDataProvider\External\Client\BinApiRequester;
use Test\CommissionRateCalculator\BinDataProvider\External\ExternalBinDataProvider;
use Test\CommissionRateCalculator\TransactionDataProvider\File\FileReader\JsonFileReader;
use Test\CommissionRateCalculator\TransactionDataProvider\File\TransactionsFromFileDataProvider;
use Test\CommissionRateCalculator\ExchangeRateProvider\Client\ApilayerDotComeExchangeRateProvider;

return [
    Test\CommissionRateCalculator\CommissionRateApp::class => function (ContainerInterface $c) {
        return new CommissionRateApp(
            new TransactionsFromFileDataProvider(
                filesDirectory: $c->get('transactions_data_dir'),
                fileReader:  new JsonFileReader()
            ),
            new ApilayerDotComeExchangeRateProvider(
                $c->get('exchange_api_key'),
                $c->get('exchange_api_url'),
            ),
            new CommissionCalculator(
                $c->get('european_countries_short_codes'),
                $c->get('european_countries_multiplier'),
                $c->get('other_countries_multiplier'),
            ),
            new ExternalBinDataProvider(new BinApiRequester($c->get('bin_api_url')))
        );
    },
    'transactions_data_dir' => function (ContainerInterface $c) {
        $rootDir = function($startDir) {
            $dir = $startDir;
            while ($dir !== '/' && !file_exists($dir . '/composer.json')) {
                $dir = dirname($dir);
            }
            return $dir !== '/' ? $dir : null;
        };

        return "{$rootDir(__DIR__)}/files";
    },
    'bin_api_url' => 'https://lookup.binlist.net/',
    //TODO Remove before pushing to repository
    'exchange_api_key' => '',
    'exchange_api_url' => 'https://api.apilayer.com/exchangerates_data/latest?base=EUR',
    'european_countries_short_codes' => [
        'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU',
        'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'
    ],
    'european_countries_multiplier' => 0.01,
    'other_countries_multiplier' => 0.02,
];
