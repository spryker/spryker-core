<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage;

use Spryker\Shared\ProductAlternativeStorage\ProductAlternativeStorageConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductAlternativeStorageConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return $this->get(ProductAlternativeStorageConstants::STORAGE_SYNC_ENABLED, true);
    }

    /**
     * @return string|null
     */
    public function getProductAlternativeSynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getProductReplacementForSynchronizationPoolName(): ?string
    {
        return null;
    }
}
