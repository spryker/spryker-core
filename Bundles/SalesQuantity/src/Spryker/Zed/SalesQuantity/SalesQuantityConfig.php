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
     * @var int|null Controls the threshold for quantity which above the quantity should not be splitted. Null value inactivates the threshold.
     */
    protected const ITEM_NONSPLIT_QUANTITY_THRESHOLD = null;

    /**
     * @var int|null
     */
    protected const BUNDLED_ITEM_NONSPLIT_QUANTITY_THRESHOLD = null;

    /**
     * @api
     *
     * @return int|null
     */
    public function findItemQuantityThreshold(): ?int
    {
        return static::ITEM_NONSPLIT_QUANTITY_THRESHOLD;
    }

    /**
     * Specification:
     * - Controls the non-splittable threshold for bundled item quantity.
     * - If bundled item quantity equals or is higher than the threshold, the item is considered non-splittable.
     * - Null value inactivates the threshold.
     *
     * @api
     *
     * @return int|null
     */
    public function getBundledItemNonSplitQuantityThreshold(): ?int
    {
        return static::BUNDLED_ITEM_NONSPLIT_QUANTITY_THRESHOLD;
    }
}
