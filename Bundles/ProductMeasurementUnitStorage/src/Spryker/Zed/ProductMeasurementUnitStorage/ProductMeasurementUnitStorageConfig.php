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
     * @deprecated Will be removed without replacement.
     *
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return true;
    }

    /**
     * @return string|null
     */
    public function getProductMeasurementUnitSynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getProductConcreteMeasurementUnitSynchronizationPoolName(): ?string
    {
        return null;
    }
}
