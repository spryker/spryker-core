<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\DummyPayment\DummyPaymentConfig;

class PaymentsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_PAYMENT_METHODS = 'payment-methods';

    protected const PAYMENT_METHOD_PRIORITY = [
        DummyPaymentConfig::PAYMENT_METHOD_NAME_INVOICE => 1,
        DummyPaymentConfig::PAYMENT_METHOD_NAME_CREDIT_CARD => 2,
    ];

    /**
     * @return int[]
     */
    public function getPaymentMethodPriority(): array
    {
        return static::PAYMENT_METHOD_PRIORITY;
    }
}
