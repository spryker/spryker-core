<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MinimumOrderValueConfig extends AbstractBundleConfig
{
    protected const MINIMUM_ORDER_VALUE_EXPENSE_TYPE = 'MINIMUM_ORDER_VALUE_EXPENSE_TYPE';

    /**
     * @return string
     */
    public function getMinimumOrderValueExpenseType(): string
    {
        return static::MINIMUM_ORDER_VALUE_EXPENSE_TYPE;
    }
}
