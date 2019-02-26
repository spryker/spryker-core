<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage;

use Spryker\Shared\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductMeasurementUnitStorageConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return $this->get(ProductMeasurementUnitStorageConstants::STORAGE_SYNC_ENABLED, true);
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
