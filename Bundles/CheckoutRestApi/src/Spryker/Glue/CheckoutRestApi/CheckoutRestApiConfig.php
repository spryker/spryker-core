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

    /**
     * @deprecated Use Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig::RESPONSE_CODE_CHECKOUT_DATA_INVALID instead.
     */
    public const RESPONSE_CODE_CHECKOUT_DATA_INVALID = '1101';
    /**
     * @deprecated Use Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig::RESPONSE_CODE_ORDER_NOT_PLACED instead.
     */
    public const RESPONSE_CODE_ORDER_NOT_PLACED = '1102';
    /**
     * @deprecated Use Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig::RESPONSE_CODE_CART_NOT_FOUND instead.
     */
    public const RESPONSE_CODE_CART_NOT_FOUND = '1103';
    /**
     * @deprecated Use Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig::RESPONSE_CODE_CART_IS_EMPTY instead.
     */
    public const RESPONSE_CODE_CART_IS_EMPTY = '1104';
    public const RESPONSE_CODE_USER_IS_NOT_SPECIFIED = '1105';
    /**
     * @deprecated Use Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig::RESPONSE_CODE_UNABLE_TO_DELETE_CART instead.
     */
    public const RESPONSE_CODE_UNABLE_TO_DELETE_CART = '1106';
    public const RESPONSE_CODE_MULTIPLE_PAYMENTS_NOT_ALLOWED = '1107';
    public const RESPONSE_CODE_INVALID_PAYMENT = '1108';

    /**
     * @deprecated Use Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig::RESPONSE_DETAILS_CHECKOUT_DATA_INVALID instead.
     */
    public const RESPONSE_DETAILS_CHECKOUT_DATA_INVALID = 'Checkout data is invalid.';
    /**
     * @deprecated Use Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig::RESPONSE_DETAILS_ORDER_NOT_PLACED instead.
     */
    public const RESPONSE_DETAILS_ORDER_NOT_PLACED = 'Order could not be placed.';
    /**
     * @deprecated Use Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig::RESPONSE_DETAILS_CART_NOT_FOUND instead.
     */
    public const RESPONSE_DETAILS_CART_NOT_FOUND = 'Cart not found.';
    /**
     * @deprecated Use Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig::RESPONSE_DETAILS_CART_IS_EMPTY instead.
     */
    public const RESPONSE_DETAILS_CART_IS_EMPTY = 'Cart is empty.';
    public const RESPONSE_DETAILS_USER_IS_NOT_SPECIFIED = 'One of Authorization or X-Anonymous-Customer-Unique-Id headers is required.';
    /**
     * @deprecated Use Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig::RESPONSE_DETAILS_UNABLE_TO_DELETE_CART instead.
     */
    public const RESPONSE_DETAILS_UNABLE_TO_DELETE_CART = 'Unable to delete cart.';
    public const RESPONSE_DETAILS_MULTIPLE_PAYMENTS_NOT_ALLOWED = 'Multiple payments are not allowed.';
    public const RESPONSE_DETAILS_INVALID_PAYMENT = 'Payment method "%s" of payment provider "%s" is invalid';

    protected const PAYMENT_REQUIRED_FIELDS = [
        'paymentMethod',
        'paymentProvider',
    ];

    protected const PAYMENT_METHOD_REQUIRED_FIELDS = [];

    /**
     * @param string $paymentMethodName
     *
     * @return array
     */
    public function getRequiredRequestDataForPaymentMethod(string $paymentMethodName): array
    {
        if (!isset(static::PAYMENT_METHOD_REQUIRED_FIELDS[$paymentMethodName])) {
            return static::PAYMENT_REQUIRED_FIELDS;
        }

        return array_merge(static::PAYMENT_REQUIRED_FIELDS, static::PAYMENT_METHOD_REQUIRED_FIELDS[$paymentMethodName]);
    }

    /**
     * @example
     * [
     *  'PaymentProvider1' => [
     *   'credit card' => 'paymentProvider1CreditCard',
     *   'invoice' => 'paymentProvider1Invoice',
     *  ],
     * ]
     *
     * @return array
     */
    public function getPaymentProviderMethodToStateMachineMapping(): array
    {
        return [];
    }
}
