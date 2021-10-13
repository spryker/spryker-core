<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityStorage\Persistence;

use Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorageQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\AvailabilityStorage\AvailabilityStorageDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\AvailabilityStorage\AvailabilityStorageConfig getConfig()
 * @method \Spryker\Zed\AvailabilityStorage\Persistence\AvailabilityStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\AvailabilityStorage\Persistence\AvailabilityStorageRepositoryInterface getRepository()
 */
class AvailabilityStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorageQuery
     */
    public function createSpyAvailabilityStorageQuery()
    {
        return SpyAvailabilityStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function getProductAbstractPropelQuery(): SpyProductAbstractQuery
    {
        return $this->getProvidedDependency(AvailabilityStorageDependencyProvider::PROPEL_QUERY_PRODUCT_ABSTRACT);
    }

    /**
     * @return \Spryker\Zed\AvailabilityStorage\Dependency\QueryContainer\AvailabilityStorageToProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(AvailabilityStorageDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\AvailabilityStorage\Dependency\QueryContainer\AvailabilityStorageToAvailabilityQueryContainerInterface
     */
    public function getAvailabilityQueryContainer()
    {
        return $this->getProvidedDependency(AvailabilityStorageDependencyProvider::QUERY_CONTAINER_AVAILABILITY);
    }
}
