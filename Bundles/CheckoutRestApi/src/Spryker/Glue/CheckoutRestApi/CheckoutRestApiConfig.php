<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CheckoutRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_CHECKOUT_DATA = 'checkout-data';
    public const RESOURCE_CHECKOUT = 'checkout';

    public const CONTROLLER_CHECKOUT_DATA = 'checkout-data-resource';
    public const CONTROLLER_CHECKOUT = 'checkout-resource';

    public const ACTION_CHECKOUT_DATA_POST = 'post';
    public const ACTION_CHECKOUT_POST = 'post';

    public const RESPONSE_CODE_CHECKOUT_DATA_INVALID = '1104';
    public const RESPONSE_CODE_ORDER_NOT_PLACED = '1105';
    public const RESPONSE_CODE_CART_NOT_FOUND = '1106';
    public const RESPONSE_CODE_CUSTOMER_EMAIL_MISSING = '1107';
    public const RESPONSE_CODE_PAYMENT_MISSING = '1108';
    public const RESPONSE_CODE_PAYMENT_INVALID = '1109';
    public const RESPONSE_CODE_SHIPPING_MISSING = '1110';
    public const RESPONSE_CODE_SHIPPING_INVALID = '1111';
    public const RESPONSE_CODE_BILLING_ADDRESS_MISSING = '1112';
    public const RESPONSE_CODE_BILLING_ADDRESS_INVALID = '1113';
    public const RESPONSE_CODE_SHIPPING_ADDRESS_MISSING = '1114';
    public const RESPONSE_CODE_SHIPPING_ADDRESS_INVALID = '1115';

    public const EXCEPTION_MESSAGE_CHECKOUT_DATA_INVALID = 'Checkout data is invalid.';
    public const EXCEPTION_MESSAGE_ORDER_NOT_PLACED = 'Order could not be placed.';
    public const EXCEPTION_MESSAGE_CART_NOT_FOUND = 'Cart could not be found.';
    public const EXCEPTION_MESSAGE_CUSTOMER_EMAIL_MISSING = 'Customer email is missing.';
    public const EXCEPTION_MESSAGE_PAYMENT_MISSING = 'Payment method data is missing.';
    public const EXCEPTION_MESSAGE_PAYMENT_INVALID = 'Payment data is invalid.';
    public const EXCEPTION_MESSAGE_SHIPPING_MISSING = 'Shipping method data is missing.';
    public const EXCEPTION_MESSAGE_SHIPPING_INVALID = 'Shipping method data is invalid.';
    public const EXCEPTION_MESSAGE_BILLING_ADDRESS_MISSING = 'Billing address data is missing.';
    public const EXCEPTION_MESSAGE_BILLING_ADDRESS_INVALID = 'Billing address data is invalid.';
    public const EXCEPTION_MESSAGE_SHIPPING_ADDRESS_MISSING = 'Shipping address data is missing.';
    public const EXCEPTION_MESSAGE_SHIPPING_ADDRESS_INVALID = 'Shipping address data is invalid.';

    protected const PAYMENT_REQUIRED_DATA_COMMON = [];
    protected const PAYMENT_REQUIRED_DATA = [];

    /**
     * @param string $methodName
     *
     * @return array
     */
    public function getRequiredPaymentDataForMethod(string $methodName): array
    {
        if (!isset(static::PAYMENT_REQUIRED_DATA[$methodName])) {
            return static::PAYMENT_REQUIRED_DATA_COMMON;
        }

        return static::PAYMENT_REQUIRED_DATA_COMMON + [$methodName => static::PAYMENT_REQUIRED_DATA[$methodName]];
    }
}
