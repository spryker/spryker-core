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
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()} instead.
     *
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return true;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getProductMeasurementUnitSynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getProductConcreteMeasurementUnitSynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getProductMeasurementUnitEventQueueName(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getProductConcreteMeasurementUnitEventQueueName(): ?string
    {
        return null;
    }
}
