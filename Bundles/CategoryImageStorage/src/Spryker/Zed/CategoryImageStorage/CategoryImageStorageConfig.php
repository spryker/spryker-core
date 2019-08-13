<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage;

use Spryker\Shared\CategoryImageStorage\CategoryImageStorageConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CategoryImageStorageConfig extends AbstractBundleConfig
{
    /**
     * @deprecated Use \Spryker\Zed\CategoryImageStorage\CategoryImageStorageConfig::getCategoryImageSynchronizationPoolName instead.
     *
     * @return string|null
     */
    public function getProductImageSynchronizationPoolName(): ?string
    {
        return $this->getCategoryImageSynchronizationPoolName();
    }

    /**
     * @return string|null
     */
    public function getCategoryImageSynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return $this->get(CategoryImageStorageConstants::STORAGE_SYNC_ENABLED, true);
    }
}
