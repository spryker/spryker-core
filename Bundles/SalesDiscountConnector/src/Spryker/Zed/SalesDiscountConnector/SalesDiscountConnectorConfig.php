<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDiscountConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesDiscountConnectorConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - This method is used to determine whether the current order should be excluded from the customer order count.
     * - Set this to `true` if you want to exclude the current order from the `customer-order-count` discount condition.
     *
     * @api
     *
     * @return bool
     */
    public function isCurrentOrderExcludedFromCount(): bool
    {
        return false;
    }
}
