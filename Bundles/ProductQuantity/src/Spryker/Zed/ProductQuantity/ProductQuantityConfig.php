<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\ProductQuantity\ProductQuantityConfig getSharedConfig()
 */
class ProductQuantityConfig extends AbstractBundleConfig
{
    /**
     * @return float
     */
    public function getDefaultMinimumQuantity(): float
    {
        return $this->getSharedConfig()->getDefaultMinimumQuantity();
    }

    /**
     * @return float
     */
    public function getDefaultInterval(): float
    {
        return $this->getSharedConfig()->getDefaultInterval();
    }
}
