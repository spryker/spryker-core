<?php
/**
 * Copy over the following configs to your config
 */

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Braintree\BraintreeConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Oms\OmsConstants;
use Spryker\Shared\Sales\SalesConstants;
use Spryker\Zed\Oms\OmsConfig;

$config[BraintreeConstants::MERCHANT_ID] = '';
$config[BraintreeConstants::PUBLIC_KEY] = '';
$config[BraintreeConstants::PRIVATE_KEY] = '';
$config[BraintreeConstants::ENVIRONMENT] = '';
$config[BraintreeConstants::ACCOUNT_ID] = '';
$config[BraintreeConstants::ACCOUNT_UNIQUE_IDENTIFIER] = '';
$config[BraintreeConstants::IS_VAULTED] = true;
$config[BraintreeConstants::IS_3D_SECURE] = true;

$config[KernelConstants::DEPENDENCY_INJECTOR_YVES] = [
    'Checkout' => [
        'Braintree',
    ],
];

$config[KernelConstants::DEPENDENCY_INJECTOR_ZED] = [
    'Payment' => [
        'Braintree',
    ],
    'Oms' => [
        'Braintree',
    ],
];

$config[OmsConstants::PROCESS_LOCATION] = [
    OmsConfig::DEFAULT_PROCESS_LOCATION,
    $config[ApplicationConstants::APPLICATION_SPRYKER_ROOT] . '/Braintree/config/Zed/Oms'
];

$config[OmsConstants::ACTIVE_PROCESSES] = [
    'BraintreePayPal01',
    'BraintreeCreditCard01',
];

$config[SalesConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING] = [
    BraintreeConstants::PAYMENT_METHOD_CREDIT_CARD => 'BraintreeCreditCard01',
    BraintreeConstants::PAYMENT_METHOD_PAY_PAL => 'BraintreePayPal01',
];
