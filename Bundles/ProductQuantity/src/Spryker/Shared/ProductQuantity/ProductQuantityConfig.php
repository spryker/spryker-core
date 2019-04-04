<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductQuantity;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ProductQuantityConfig extends AbstractSharedConfig
{
    protected const DEFAULT_MIN_QUANTITY = 1.0;
    protected const DEFAULT_INTERVAL = 1.0;

    /**
     * @return float
     */
    public function getDefaultMinimumQuantity(): float
    {
        return static::DEFAULT_MIN_QUANTITY;
    }

    /**
     * @return float
     */
    public function getDefaultInterval(): float
    {
        return static::DEFAULT_INTERVAL;
    }
}
