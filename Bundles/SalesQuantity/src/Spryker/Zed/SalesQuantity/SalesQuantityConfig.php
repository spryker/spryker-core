<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesQuantityConfig extends AbstractBundleConfig
{
    /**
     * @const int|null Controls the threshold for quantity which above the quantity should not be splitted. Null value inactivates the threshold.
     */
    protected const ITEM_NONSPLIT_QUANTITY_THRESHOLD = null;

    /**
     * @return int|null
     */
    public function findItemQuantityThreshold(): ?int
    {
        return static::ITEM_NONSPLIT_QUANTITY_THRESHOLD;
    }
}
