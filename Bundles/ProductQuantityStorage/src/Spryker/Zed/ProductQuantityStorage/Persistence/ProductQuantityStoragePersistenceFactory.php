<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityStorage\Persistence;

use Orm\Zed\ProductQuantity\Persistence\SpyProductQuantityQuery;
use Orm\Zed\ProductQuantityStorage\Persistence\SpyProductQuantityStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductQuantityStorage\Persistence\Propel\Mapper\ProductQuantityStorageMapper;
use Spryker\Zed\ProductQuantityStorage\Persistence\Propel\Mapper\ProductQuantityStorageMapperInterface;
use Spryker\Zed\ProductQuantityStorage\ProductQuantityStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductQuantityStorage\ProductQuantityStorageConfig getConfig()
 * @method \Spryker\Zed\ProductQuantityStorage\Persistence\ProductQuantityStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductQuantityStorage\Persistence\ProductQuantityStorageRepositoryInterface getRepository()
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

    /**
     * @return \Orm\Zed\ProductQuantity\Persistence\SpyProductQuantityQuery
     */
    public function getProductQuantityQuery(): SpyProductQuantityQuery
    {
        return $this->getProvidedDependency(ProductQuantityStorageDependencyProvider::PROPEL_QUERY_PRODUCT_QUANTITY);
    }
}
