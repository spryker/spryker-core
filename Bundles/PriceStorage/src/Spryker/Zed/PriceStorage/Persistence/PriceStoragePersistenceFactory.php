<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceStorage\Persistence;

use Orm\Zed\PriceStorage\Persistence\SpyPriceAbstractStorageQuery;
use Orm\Zed\PriceStorage\Persistence\SpyPriceConcreteStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\PriceStorage\PriceStorageDependencyProvider;

/**
 * @method \Spryker\Zed\PriceStorage\PriceStorageConfig getConfig()
 * @method \Spryker\Zed\PriceStorage\Persistence\PriceStorageQueryContainer getQueryContainer()
 */
class PriceStoragePersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\PriceStorage\Persistence\SpyPriceAbstractStorageQuery
     */
    public function createSpyPriceAbstractStorageQuery()
    {
        return SpyPriceAbstractStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\PriceStorage\Persistence\SpyPriceConcreteStorageQuery
     */
    public function createSpyPriceConcreteStorageQuery()
    {
        return SpyPriceConcreteStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\PriceStorage\Dependency\QueryContainer\PriceStorageToPriceQueryContainerInterface
     */
    public function getPriceQueryContainer()
    {
        return $this->getProvidedDependency(PriceStorageDependencyProvider::QUERY_CONTAINER_PRICE);
    }

}
