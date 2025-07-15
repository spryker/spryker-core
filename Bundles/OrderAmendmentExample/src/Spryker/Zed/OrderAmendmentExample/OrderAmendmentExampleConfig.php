<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderAmendmentExample;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class OrderAmendmentExampleConfig extends AbstractBundleConfig
{
    /**
     * @var list<string>
     */
    protected const ASYNC_ORDER_AMENDMENT_PAYMENT_METHOD_NAMES = [
        'dummyPaymentInvoice',
    ];

    /**
     * Specification:
     * - Returns the payment method names for which the async order amendment flow should be applied.
     *
     * @api
     *
     * @return list<string>
     */
    public function getAsyncOrderAmendmentPaymentMethodNames(): array
    {
        return static::ASYNC_ORDER_AMENDMENT_PAYMENT_METHOD_NAMES;
    }
}
