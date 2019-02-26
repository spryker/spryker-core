<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupStorage;

use Spryker\Shared\ProductGroupStorage\ProductGroupStorageConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductGroupStorageConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return $this->get(ProductGroupStorageConstants::STORAGE_SYNC_ENABLED, true);
    }

    /**
     * @return string|null
     */
    public function getProductGroupSynchronizationPoolName(): ?string
    {
        return null;
    }
}
