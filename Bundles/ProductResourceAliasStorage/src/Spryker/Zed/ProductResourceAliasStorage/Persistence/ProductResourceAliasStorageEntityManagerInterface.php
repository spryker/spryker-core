<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductResourceAliasStorage\Persistence;

use Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage;
use Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage;

interface ProductResourceAliasStorageEntityManagerInterface
{
    /**
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage $productAbstractStorageEntity
     *
     * @return void
     */
    public function saveProductAbstractStorageEntity(SpyProductAbstractStorage $productAbstractStorageEntity): void;

    /**
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage $productConcreteStorageEntity
     *
     * @return void
     */
    public function saveProductConcreteStorageEntity(SpyProductConcreteStorage $productConcreteStorageEntity): void;
}
