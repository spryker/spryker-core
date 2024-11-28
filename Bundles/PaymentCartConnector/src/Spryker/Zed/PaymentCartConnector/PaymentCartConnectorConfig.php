<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentCartConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class PaymentCartConnectorConfig extends AbstractBundleConfig
{
    /**
     * @var list<string>
     */
    protected const EXCLUDED_PAYMENT_METHODS = [];

    /**
     * Specification:
     * - Retrieves the list of payment methods used to exclude certain payments from removal in the quote.
     * - Example: ['Dummy payment'], the value is the payment method name.
     *
     * @api
     *
     * @return list<string>
     */
    public function getExcludedPaymentMethods(): array
    {
        return static::EXCLUDED_PAYMENT_METHODS;
    }
}
