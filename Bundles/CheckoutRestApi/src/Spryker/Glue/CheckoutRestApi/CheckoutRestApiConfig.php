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

    public const RESPONSE_CODE_DATA_INVALID = '1104';
    public const RESPONSE_CODE_ORDER_NOT_PLACED = '1105';
    public const RESPONSE_CODE_QUOTE_NOT_FOUND = '1106';

    public const EXCEPTION_MESSAGE_DATA_INVALID = 'Quote data is invalid.';
    public const EXCEPTION_MESSAGE_ORDER_NOT_PLACED = 'Order could not be placed.';
    public const EXCEPTION_MESSAGE_QUOTE_NOT_FOUND = 'Quote could not be found.';

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
