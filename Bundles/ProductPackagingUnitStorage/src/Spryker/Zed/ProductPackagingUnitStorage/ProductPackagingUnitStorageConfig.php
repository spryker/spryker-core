<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductPackagingUnitStorageConfig extends AbstractBundleConfig
{
    /**
     * @deprecated Use `\Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()` instead.
     *
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return true;
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
