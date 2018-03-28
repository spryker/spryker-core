<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityStorage\Persistence;

use Orm\Zed\ProductQuantityStorage\Persistence\SpyProductQuantityStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductQuantityStorage\Persistence\Propel\Mapper\ProductQuantityStorageMapper;
use Spryker\Zed\ProductQuantityStorage\Persistence\Propel\Mapper\ProductQuantityStorageMapperInterface;

/**
 * @method \Spryker\Zed\ProductQuantityStorage\ProductQuantityStorageConfig getConfig()
 */
class ProductQuantityStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductQuantityStorage\Persistence\SpyProductQuantityStorageQuery
     */
    public function createProductQuantityStorageQuery(): SpyProductQuantityStorageQuery
    {
        return SpyProductQuantityStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductQuantityStorage\Persistence\Propel\Mapper\ProductQuantityStorageMapperInterface
     */
    public function createProductQuantityStorageMapper(): ProductQuantityStorageMapperInterface
    {
        return new ProductQuantityStorageMapper();
    }
}
