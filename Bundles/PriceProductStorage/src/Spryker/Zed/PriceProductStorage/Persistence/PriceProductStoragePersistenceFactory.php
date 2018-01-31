<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Persistence;

use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorageQuery;
use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\PriceProductStorage\PriceProductStorageDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProductStorage\PriceProductStorageConfig getConfig()
 * @method \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface getQueryContainer()
 */
class PriceProductStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorageQuery
     */
    public function createSpyPriceAbstractStorageQuery()
    {
        return SpyPriceProductAbstractStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorageQuery
     */
    public function createSpyPriceConcreteStorageQuery()
    {
        return SpyPriceProductConcreteStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Dependency\QueryContainer\PriceProductStorageToPriceProductQueryContainerInterface
     */
    public function getPriceProductQueryContainer()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::QUERY_CONTAINER_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Dependency\QueryContainer\PriceProductStorageToProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }
}
