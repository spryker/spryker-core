<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence;

use Orm\Zed\Oms\Persistence\Base\SpyOmsProductReservationQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery;
use Orm\Zed\Oms\Persistence\SpyOmsStateMachineLockQuery;
use Orm\Zed\Oms\Persistence\SpyOmsTransitionLogQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Oms\OmsConfig getConfig()
 * @method \Spryker\Zed\Oms\Persistence\OmsQueryContainer getQueryContainer()
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
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function createSalesOrderItemQuery()
    {
        return SpySalesOrderItemQuery::create();
    }

    /**
     * @return \Orm\Zed\StateMachine\Persistence\SpyOmsStateMachineLockQuery
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

}
