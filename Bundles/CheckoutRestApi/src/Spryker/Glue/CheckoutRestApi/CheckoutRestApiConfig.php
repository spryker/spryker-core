<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig as SharedCheckoutRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig getSharedConfig()
 */
class CheckoutRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_CHECKOUT_DATA = 'checkout-data';
    public const RESOURCE_CHECKOUT = 'checkout';

    public const CONTROLLER_CHECKOUT_DATA = 'checkout-data-resource';
    public const CONTROLLER_CHECKOUT = 'checkout-resource';

    public const ACTION_CHECKOUT_DATA_POST = 'post';
    public const ACTION_CHECKOUT_DATA_GET = 'get';
    public const ACTION_CHECKOUT_POST = 'post';
    public const ACTION_CHECKOUT_GET = 'get';

    public const RESPONSE_CODE_CHECKOUT_DATA_INVALID = '1101';
    public const RESPONSE_CODE_ORDER_NOT_PLACED = '1102';
    public const RESPONSE_CODE_CART_NOT_FOUND = '1103';
    public const RESPONSE_CODE_CART_IS_EMPTY = '1104';
    public const RESPONSE_CODE_USER_IS_NOT_SPECIFIED = '1105';
    public const RESPONSE_CODE_UNABLE_TO_DELETE_CART = '1106';
    public const RESPONSE_CODE_MULTIPLE_PAYMENTS_NOT_ALLOWED = '1107';
    public const RESPONSE_CODE_INVALID_PAYMENT = '1108';

    public const RESPONSE_DETAILS_CHECKOUT_DATA_INVALID = 'Checkout data is invalid.';
    public const RESPONSE_DETAILS_ORDER_NOT_PLACED = 'Order could not be placed.';
    public const RESPONSE_DETAILS_CART_NOT_FOUND = 'Cart not found.';
    public const RESPONSE_DETAILS_CART_IS_EMPTY = 'Cart is empty.';
    public const RESPONSE_DETAILS_USER_IS_NOT_SPECIFIED = 'One of Authorization or X-Anonymous-Customer-Unique-Id headers is required.';
    public const RESPONSE_DETAILS_UNABLE_TO_DELETE_CART = 'Unable to delete cart.';
    public const RESPONSE_DETAILS_MULTIPLE_PAYMENTS_NOT_ALLOWED = 'Multiple payments are not allowed.';
    public const RESPONSE_DETAILS_INVALID_PAYMENT = 'Payment method "%s" of payment provider "%s" is invalid';

    protected const PAYMENT_REQUIRED_FIELDS = [
        'paymentMethod',
        'paymentProvider',
    ];

    protected const PAYMENT_METHOD_REQUIRED_FIELDS = [];
    protected const ERROR_IDENTIFIER_TO_REST_ERROR_MAPPING = [
        SharedCheckoutRestApiConfig::ERROR_IDENTIFIER_UNABLE_TO_DELETE_CART => [
            RestErrorMessageTransfer::CODE => self::RESPONSE_CODE_UNABLE_TO_DELETE_CART,
            RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
            RestErrorMessageTransfer::DETAIL => self::RESPONSE_DETAILS_UNABLE_TO_DELETE_CART,
        ],
        SharedCheckoutRestApiConfig::ERROR_IDENTIFIER_ORDER_NOT_PLACED => [
            RestErrorMessageTransfer::CODE => self::RESPONSE_CODE_ORDER_NOT_PLACED,
            RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
            RestErrorMessageTransfer::DETAIL => self::RESPONSE_DETAILS_ORDER_NOT_PLACED,
        ],
        SharedCheckoutRestApiConfig::ERROR_IDENTIFIER_CART_IS_EMPTY => [
            RestErrorMessageTransfer::CODE => self::RESPONSE_CODE_CART_IS_EMPTY,
            RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
            RestErrorMessageTransfer::DETAIL => self::RESPONSE_DETAILS_CART_IS_EMPTY,
        ],
        SharedCheckoutRestApiConfig::ERROR_IDENTIFIER_CART_NOT_FOUND => [
            RestErrorMessageTransfer::CODE => self::RESPONSE_CODE_CART_NOT_FOUND,
            RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
            RestErrorMessageTransfer::DETAIL => self::RESPONSE_DETAILS_CART_NOT_FOUND,
        ],
        SharedCheckoutRestApiConfig::ERROR_IDENTIFIER_CHECKOUT_DATA_INVALID => [
            RestErrorMessageTransfer::CODE => self::RESPONSE_CODE_CHECKOUT_DATA_INVALID,
            RestErrorMessageTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
            RestErrorMessageTransfer::DETAIL => self::RESPONSE_DETAILS_CHECKOUT_DATA_INVALID,
        ],
    ];

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

    /**
     * @return array
     */
    public function getErrorIdentifierToRestErrorMapping(): array
    {
        return static::ERROR_IDENTIFIER_TO_REST_ERROR_MAPPING;
    }
}
