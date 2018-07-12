<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelationStorage;

use Spryker\Zed\ProductRelationStorage\ProductRelationStorageConfig;

class ProductRelationStorageConfigMock extends ProductRelationStorageConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue()
    {
        return false;
    }
}
