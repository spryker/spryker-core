<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Persistence;

use Functional\Spryker\Zed\ProductOption\Mock\ProductQueryContainer;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery;
use Orm\Zed\Availability\Persistence\SpyAvailabilityQuery;
use Spryker\Zed\Availability\AvailabilityDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Stock\Persistence\StockQueryContainer;

/**
 * @method \Spryker\Zed\Availability\AvailabilityConfig getConfig()
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer getQueryContainer()
 */
class AvailabilityPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return SpyAvailabilityQuery
     */
    public function createSpyAvailabilityQuery()
    {
        return SpyAvailabilityQuery::create();
    }

    /**
     * @return SpyAvailabilityAbstractQuery
     */
    public function createSpyAvailabilityAbstractQuery()
    {
        return SpyAvailabilityAbstractQuery::create();
    }

    /**
     * @return ProductQueryContainer
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return StockQueryContainer
     */
    public function getStockQueryContainer()
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::QUERY_CONTAINER_STOCK);
    }
}
