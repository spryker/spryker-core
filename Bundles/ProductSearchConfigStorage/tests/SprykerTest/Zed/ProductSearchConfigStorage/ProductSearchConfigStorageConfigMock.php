<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSearchConfigStorage;

use Spryker\Zed\ProductSearchConfigStorage\ProductSearchConfigStorageConfig;

class ProductSearchConfigStorageConfigMock extends ProductSearchConfigStorageConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue()
    {
        return false;
    }
}
