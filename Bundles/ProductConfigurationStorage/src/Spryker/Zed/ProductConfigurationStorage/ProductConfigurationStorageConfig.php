<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage;

use Pyz\Zed\Synchronization\SynchronizationConfig;
use Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig;

class ProductConfigurationStorageConfig extends SynchronizationBehaviorConfig
{
    /**
     * @api
     *
     * @return string|null
     */
    public function getProductConfigurationSynchronizationPoolName(): ?string
    {
        return SynchronizationConfig::DEFAULT_SYNCHRONIZATION_POOL_NAME;
    }
}
