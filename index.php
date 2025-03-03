<?php

require_once 'vendor/autoload.php';

use DI\NotFoundException;
use Di\DependencyException;
use Test\CommissionRateCalculator\CommissionRateApp;

$container = new DI\Container(require_once "config/di_config.php");

try {
    $result = $container->get(CommissionRateApp::class)->run();

    foreach ($result as $commissionRate) {
        echo round($commissionRate, 2);
    }
} catch (NotFoundException | DependencyException $exception) {
    echo "Error: " . $exception->getMessage();
}
