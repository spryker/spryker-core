<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAlternativeStorage;

use Spryker\Zed\ProductAlternativeStorage\ProductAlternativeStorageConfig;

class ProductAlternativeStorageConfigMock extends ProductAlternativeStorageConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return false;
    }
}
