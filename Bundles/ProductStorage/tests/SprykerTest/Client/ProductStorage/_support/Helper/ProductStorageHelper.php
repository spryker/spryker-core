<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductStorage\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductAbstractStorageBuilder;
use Generated\Shared\Transfer\ProductAbstractStorageTransfer;

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */
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
