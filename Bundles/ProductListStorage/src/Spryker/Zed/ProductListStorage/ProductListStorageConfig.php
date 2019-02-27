<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage;

use Spryker\Shared\ProductListStorage\ProductListStorageConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductListStorageConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return $this->get(ProductListStorageConstants::STORAGE_SYNC_ENABLED, true);
    }

    /**
     * @return string|null
     */
    public function getProductAbstractProductListSynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getProductConcreteProductListSynchronizationPoolName(): ?string
    {
        return null;
    }
}
