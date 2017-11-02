<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage;

use Spryker\Client\Kernel\AbstractBundleConfig;

class StorageConfig extends AbstractBundleConfig
{
    /**
     * @return int
     */
    public function getStorageCacheIncrementalStrategyKeySizeLimit()
    {
        return 1000;
    }

    /**
     * @return int
     */
    public function getStorageCacheTtl()
    {
        return 86400;
    }

    /**
     * @return array
     */
    public function getAllowedGetParametersList()
    {
        return [];
    }
}
