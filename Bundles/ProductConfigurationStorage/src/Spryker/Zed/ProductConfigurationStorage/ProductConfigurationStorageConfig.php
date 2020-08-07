<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage;

use Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig;

class ProductConfigurationStorageConfig extends SynchronizationBehaviorConfig
{
    /**
     * @api
     *
     *
     * @return bool
     */
    public function isSynchronizationEnabled(): bool
    {
        return true;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getProductConfigurationSynchronizationPoolName(): ?string
    {
        return null;
    }
}
