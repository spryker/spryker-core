<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnitStorage;

use Spryker\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageConfig;

class ProductPackagingUnitStorageConfigMock extends ProductPackagingUnitStorageConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return false;
    }
}
