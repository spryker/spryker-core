<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductQuantityStorageConfig extends AbstractBundleConfig
{
    /**
     * @return null|string
     */
    public function getProductQuantitySynchronizationPoolName()
    {
        return null;
    }
}
