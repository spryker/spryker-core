<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesConfigurableBundleConfig extends AbstractBundleConfig
{
    /**
     * @const int|null Controls the threshold for item quantity inside configurable bundle which above the quantity should not be split. Null value inactivates the threshold.
     */
    protected const CONFIGURABLE_BUNDLE_ITEM_NONSPLIT_QUANTITY_THRESHOLD = null;

    /**
     * @return int|null
     */
    public function findConfigurableBundleItemQuantityThreshold(): ?int
    {
        return static::CONFIGURABLE_BUNDLE_ITEM_NONSPLIT_QUANTITY_THRESHOLD;
    }
}
