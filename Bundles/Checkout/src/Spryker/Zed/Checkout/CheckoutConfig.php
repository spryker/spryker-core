<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CheckoutConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    public const ERROR_CODE_CUSTOMER_ALREADY_REGISTERED = 4001;

    /**
     * @var int
     */
    public const ERROR_CODE_PRODUCT_UNAVAILABLE = 4002;

    /**
     * @var string
     */
    public const ERROR_CODE_CART_AMOUNT_DIFFERENT = '4003';

    /**
     * @var int
     */
    protected const SAVE_ORDER_TRANSACTION_MAX_ATTEMPTS = 5;

    /**
     * Specification:
     * - Returns the maximum number of attempts to save an order.
     *
     * @api
     *
     * @return int
     */
    public function getSaveOrderTransactionMaxAttempts(): int
    {
        return static::SAVE_ORDER_TRANSACTION_MAX_ATTEMPTS;
    }
}
