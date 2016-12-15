<?php
/**
 * Copy over the following configs to your config
 */

use Spryker\Shared\Braintree\BraintreeConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Oms\OmsConstants;
use Spryker\Shared\Sales\SalesConstants;
use Spryker\Zed\Oms\OmsConfig;

// the mode of the transaction, either development, integration, sandbox, production, qa (required)
$config[BraintreeConstants::ENVIRONMENT] = '';

// the id of the merchant used (required)
$config[BraintreeConstants::MERCHANT_ID] = '';

// the public key given by the defined merchant account (required)
$config[BraintreeConstants::PUBLIC_KEY] = '';

// the private key given by the defined merchant account (required)
$config[BraintreeConstants::PRIVATE_KEY] = '';

// merchant account id specifying the currency (Marketplace master merchant is used by default)
$config[BraintreeConstants::ACCOUNT_ID] = '';

// merchant account unique identifier (Marketplace master merchant is used by default)
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
    $config[KernelConstants::SPRYKER_ROOT] . '/Braintree/config/Zed/Oms'
];

$config[OmsConstants::ACTIVE_PROCESSES] = [
    'BraintreePayPal01',
    'BraintreeCreditCard01',
];

$config[SalesConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING] = [
    BraintreeConstants::PAYMENT_METHOD_CREDIT_CARD => 'BraintreeCreditCard01',
    BraintreeConstants::PAYMENT_METHOD_PAY_PAL => 'BraintreePayPal01',
];
