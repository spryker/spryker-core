<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence;

use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservationChangeVersionQuery;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservationLastExportedVersionQuery;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservationQuery;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservationStoreQuery;
use Orm\Zed\Oms\Persistence\SpyOmsStateMachineLockQuery;
use Orm\Zed\Oms\Persistence\SpyOmsTransitionLogQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Oms\OmsDependencyProvider;

/**
 * @method \Spryker\Zed\Oms\OmsConfig getConfig()
 * @method \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface getQueryContainer()
 */
class OmsPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsTransitionLogQuery
     */
    public function createOmsTransitionLogQuery()
    {
        return SpyOmsTransitionLogQuery::create();
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery
     */
    public function createOmsOrderProcessQuery()
    {
        return SpyOmsOrderProcessQuery::create();
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery
     */
    public function createOmsOrderItemStateQuery()
    {
        return SpyOmsOrderItemStateQuery::create();
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsStateMachineLockQuery
     */
    public function createOmsStateMachineLockQuery()
    {
        return SpyOmsStateMachineLockQuery::create();
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationQuery
     */
    public function createOmsProductReservationQuery()
    {
        return SpyOmsProductReservationQuery::create();
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationStoreQuery
     */
    public function createOmsProductReservationStoreQuery()
    {
        return SpyOmsProductReservationStoreQuery::create();
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationChangeVersionQuery
     */
    public function createOmsProductReservationChangeVersionQuery()
    {
        return SpyOmsProductReservationChangeVersionQuery::create();
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationLastExportedVersionQuery
     */
    public function createOmsProductReservationExportedVersionQuery()
    {
        return SpyOmsProductReservationLastExportedVersionQuery::create();
    }

    /**
     * @return \Spryker\Zed\Oms\Dependency\QueryContainer\OmsToSalesInterface
     */
    public function getSalesQueryContainer()
    {
        return $this->getProvidedDependency(OmsDependencyProvider::QUERY_CONTAINER_SALES);
    }
}
