<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductDiscontinuedConfig extends AbstractBundleConfig
{
    public const DEFAULT_DAYS_AMOUNT_BEFORE_PRODUCT_DEACTIVATE = 180;

    /**
     * @return int
     */
    public function getDaysAmountBeforeProductDeactivate(): int
    {
        return static::DEFAULT_DAYS_AMOUNT_BEFORE_PRODUCT_DEACTIVATE;
    }
}
