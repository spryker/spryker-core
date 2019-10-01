<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery;
use Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductConcretePackagingStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductPackagingUnitStorage\Persistence\Mapper\ProductPackagingUnitStorageMapper;
use Spryker\Zed\ProductPackagingUnitStorage\Persistence\Mapper\ProductPackagingUnitStorageMapperInterface;
use Spryker\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageConfig getConfig()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageRepositoryInterface getRepository()
 */
class ProductPackagingUnitStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductConcretePackagingStorageQuery
     */
    public function createSpyProductConcretePackagingStorageQuery(): SpyProductConcretePackagingStorageQuery
    {
        return SpyProductConcretePackagingStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnitStorage\Persistence\Mapper\ProductPackagingUnitStorageMapperInterface
     */
    public function createProductPackagingUnitStorageMapper(): ProductPackagingUnitStorageMapperInterface
    {
        return new ProductPackagingUnitStorageMapper();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function getSpyProductQuery(): SpyProductQuery
    {
        return $this->getProvidedDependency(ProductPackagingUnitStorageDependencyProvider::PROPEL_QUERY_PRODUCT);
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery
     */
    public function getProductPackagingUnitQuery(): SpyProductPackagingUnitQuery
    {
        return $this->getProvidedDependency(ProductPackagingUnitStorageDependencyProvider::PROPEL_QUERY_PRODUCT_PACKAGING_UNIT);
    }
}
