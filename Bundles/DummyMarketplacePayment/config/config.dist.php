<?php

/**
 * Copy over the following configs to your config
 */

use Spryker\Shared\DummyMarketplacePayment\DummyMarketplacePaymentConfig;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Oms\OmsConstants;
use Spryker\Shared\Sales\SalesConstants;
use Spryker\Zed\Oms\OmsConfig;

$config[OmsConstants::PROCESS_LOCATION] = [
    OmsConfig::DEFAULT_PROCESS_LOCATION,
    $config[KernelConstants::SPRYKER_ROOT] . '/DummyMarketplacePayment/config/Zed/Oms',
];

$config[OmsConstants::ACTIVE_PROCESSES] = [
    'MarketplacePayment01',
];

$config[SalesConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING] = [
    DummyMarketplacePaymentConfig::PAYMENT_METHOD_DUMMY_MARKETPLACE_PAYMENT_INVOICE => 'MarketplacePayment01',
];
