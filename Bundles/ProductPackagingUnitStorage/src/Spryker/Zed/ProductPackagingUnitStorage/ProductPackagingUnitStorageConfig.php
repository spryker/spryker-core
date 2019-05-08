<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage;

use Spryker\Shared\ProductPackagingUnitStorage\ProductPackagingUnitStorageConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductPackagingUnitStorageConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return $this->get(ProductPackagingUnitStorageConstants::STORAGE_SYNC_ENABLED, true);
    }

    /**
     * @deprecated Use ProductPackagingUnitStorageConfig::getProductAbstractPackagingSynchronizationPoolName() instead.
     *
     * @return string|null
     */
    public function getProductPackagingUnitSynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getProductAbstractPackagingSynchronizationPoolName(): ?string
    {
        return null;
    }
}
