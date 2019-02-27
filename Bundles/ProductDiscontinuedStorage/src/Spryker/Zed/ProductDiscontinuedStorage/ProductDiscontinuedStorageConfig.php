<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage;

use Spryker\Shared\ProductDiscontinuedStorage\ProductDiscontinuedStorageConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductDiscontinuedStorageConfig extends AbstractBundleConfig
{
    /**
     * @return string|null
     */
    public function getProductDiscontinuedSynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return $this->get(ProductDiscontinuedStorageConstants::STORAGE_SYNC_ENABLED, true);
    }
}
