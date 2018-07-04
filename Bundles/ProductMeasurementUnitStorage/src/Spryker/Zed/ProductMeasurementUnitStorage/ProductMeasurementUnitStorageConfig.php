<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductMeasurementUnitStorageConfig extends AbstractBundleConfig
{
    /**
     * @return null| string
     */
    public function getProductMeasurementUnitSynchronizationPoolName()
    {
        return null;
    }

    /**
     * @return null| string
     */
    public function getProductConcreteMeasurementUnitSynchronizationPoolName(): ?string
    {
        return null;
    }
}
