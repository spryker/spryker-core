<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence;

use Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyPriceProductAbstractStorageQuery;
use Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyPriceProductConcreteStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageConfig getConfig()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageQueryContainerInterface getQueryContainer()
 */
class ProductPackagingUnitStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyPriceProductAbstractStorageQuery
     */
    public function createSpyPriceAbstractStorageQuery()
    {
        return SpyPriceProductAbstractStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyPriceProductConcreteStorageQuery
     */
    public function createSpyPriceConcreteStorageQuery()
    {
        return SpyPriceProductConcreteStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnitStorage\Dependency\QueryContainer\PriceProductStorageToPriceProductQueryContainerInterface
     */
    public function getPriceProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductPackagingUnitStorageDependencyProvider::QUERY_CONTAINER_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnitStorage\Dependency\QueryContainer\PriceProductStorageToProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductPackagingUnitStorageDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }
}
