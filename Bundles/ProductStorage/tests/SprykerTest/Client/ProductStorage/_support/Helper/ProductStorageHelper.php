<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductStorage\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductAbstractStorageBuilder;
use Generated\Shared\Transfer\ProductAbstractStorageTransfer;

class ProductStorageHelper extends Module
{
    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductAbstractStorageTransfer
     */
    public function haveProductAbstractStorage(array $seedData = []): ProductAbstractStorageTransfer
    {
        return (new ProductAbstractStorageBuilder($seedData))->build();
    }
}
