<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnItemQuery;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnReasonQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SalesReturn\Persistence\Propel\Mapper\ReturnMapper;
use Spryker\Zed\SalesReturn\Persistence\Propel\Mapper\ReturnReasonMapper;
use Spryker\Zed\SalesReturn\SalesReturnDependencyProvider;

/**
 * @method \Spryker\Zed\SalesReturn\SalesReturnConfig getConfig()
 * @method \Spryker\Zed\SalesReturn\Persistence\SalesReturnEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesReturn\Persistence\SalesReturnRepositoryInterface getRepository()
 */
class SalesReturnPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery
     */
    public function getSalesReturnPropelQuery(): SpySalesReturnQuery
    {
        return SpySalesReturnQuery::create();
    }

    /**
     * @return \Orm\Zed\SalesReturn\Persistence\SpySalesReturnItemQuery
     */
    public function getSalesReturnItemPropelQuery(): SpySalesReturnItemQuery
    {
        return SpySalesReturnItemQuery::create();
    }

    /**
     * @return \Orm\Zed\SalesReturn\Persistence\SpySalesReturnReasonQuery
     */
    public function getSalesReturnReasonPropelQuery(): SpySalesReturnReasonQuery
    {
        return SpySalesReturnReasonQuery::create();
    }

    /**
     * @return \Spryker\Zed\SalesReturn\Persistence\Propel\Mapper\ReturnReasonMapper
     */
    public function createReturnReasonMapper(): ReturnReasonMapper
    {
        return new ReturnReasonMapper();
    }

    /**
     * @return \Spryker\Zed\SalesReturn\Persistence\Propel\Mapper\ReturnMapper
     */
    public function createReturnMapper(): ReturnMapper
    {
        return new ReturnMapper();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function getSalesOrderItemPropelQuery(): SpySalesOrderItemQuery
    {
        return $this->getProvidedDependency(SalesReturnDependencyProvider::PROPEL_QUERY_SALES_ORDER_ITEM);
    }
}
