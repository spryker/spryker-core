<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Storage;

use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\Storage\StorageConstants;

class StorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns the name of the strategy to be used for the StorageKeyCache.
     *
     * @api
     *
     * @return string
     */
    public function getStorageKeyCacheStrategy()
    {
        return $this->get(StorageConstants::STORAGE_CACHE_STRATEGY, StorageConstants::STORAGE_CACHE_STRATEGY_REPLACE);
    }
}
