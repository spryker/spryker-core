<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence;

use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistoryQuery;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderCommentQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesExpenseMapper;
use Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesExpenseMapperInterface;

/**
 * @method \Spryker\Zed\Sales\SalesConfig getConfig()
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 */
class SalesPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function createSalesOrderQuery()
    {
        return SpySalesOrderQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function createSalesOrderItemQuery()
    {
        return SpySalesOrderItemQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpenseQuery
     */
    public function createSalesExpenseQuery()
    {
        return SpySalesExpenseQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery
     */
    public function createSalesOrderAddressQuery()
    {
        return SpySalesOrderAddressQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderCommentQuery
     */
    public function createSalesOrderCommentQuery()
    {
        return SpySalesOrderCommentQuery::create();
    }

    /**
     * @deprecated Will be removed with the next major
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistoryQuery
     */
    public function createOmsOrderItemStateHistoryQuery()
    {
        return SpyOmsOrderItemStateHistoryQuery::create();
    }

    /**
     * @return \Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesExpenseMapperInterface
     */
    public function createSalesExpenseMapper(): SalesExpenseMapperInterface
    {
        return new SalesExpenseMapper();
    }
}
