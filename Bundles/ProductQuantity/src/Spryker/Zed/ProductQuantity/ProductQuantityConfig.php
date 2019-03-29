<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductQuantityConfig extends AbstractBundleConfig
{
    protected const MIN_QUANTITY = 1.0;
    protected const INTERVAL = 1.0;

    /**
     * @return float
     */
    public function getMinimumQuantity(): float
    {
        return static::MIN_QUANTITY;
    }

    /**
     * @return float
     */
    public function getInterval(): float
    {
        return static::INTERVAL;
    }
}
