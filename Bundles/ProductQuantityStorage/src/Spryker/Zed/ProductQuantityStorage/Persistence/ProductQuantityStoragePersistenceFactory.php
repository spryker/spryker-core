<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityStorage\Persistence;

use Orm\Zed\ProductQuantityStorage\Persistence\SpyProductQuantityStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductQuantityStorage\Persistence\Propel\Mapper\ProductQuantityStorageMapper;

/**
 * @method \Spryker\Zed\ProductQuantityStorage\ProductQuantityStorageConfig getConfig()
 */
class ProductQuantityStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductQuantityStorage\Persistence\SpyProductQuantityStorageQuery
     */
    public function createProductQuantityStorageQuery()
    {
        return SpyProductQuantityStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductQuantityStorage\Persistence\Propel\Mapper\ProductQuantityStorageMapperInterface
     */
    public function createProductQuantityStorageMapper()
    {
        return new ProductQuantityStorageMapper();
    }
}
