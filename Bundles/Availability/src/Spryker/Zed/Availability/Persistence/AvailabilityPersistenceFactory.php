<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Persistence;

use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery;
use Orm\Zed\Availability\Persistence\SpyAvailabilityQuery;
use Spryker\Zed\Availability\AvailabilityDependencyProvider;
use Spryker\Zed\Availability\Dependency\QueryContainer\AvailabilityToProductQueryContainerInterface;
use Spryker\Zed\Availability\Persistence\Mapper\AvailabilityMapper;
use Spryker\Zed\Availability\Persistence\Mapper\AvailabilityMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Availability\AvailabilityConfig getConfig()
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface getRepository()
 */
class AvailabilityPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityQuery
     */
    public function createSpyAvailabilityQuery()
    {
        return SpyAvailabilityQuery::create();
    }

    /**
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function createSpyAvailabilityAbstractQuery()
    {
        return SpyAvailabilityAbstractQuery::create();
    }

    /**
     * @return \Spryker\Zed\Availability\Persistence\Mapper\AvailabilityMapperInterface
     */
    public function createAvailabilityMapper(): AvailabilityMapperInterface
    {
        return new AvailabilityMapper();
    }

    /**
     * @return \Spryker\Zed\Availability\Dependency\QueryContainer\AvailabilityToProductQueryContainerInterface
     */
    public function getProductQueryContainer(): AvailabilityToProductQueryContainerInterface
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }
}
