<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage;

use Spryker\Shared\ProductImageStorage\ProductImageStorageConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductImageStorageConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return $this->get(ProductImageStorageConstants::STORAGE_SYNC_ENABLED, true);
    }

    /**
     * @return string|null
     */
    public function getProductImageSynchronizationPoolName(): ?string
    {
        return null;
    }
}
