<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SalesStatistics\Persistence\Propel\Mapper\SalesStatisticsMapper;
use Spryker\Zed\SalesStatistics\SalesStatisticsDependencyProvider;

/**
 * @method \Spryker\Zed\SalesStatistics\SalesStatisticsConfig getConfig()
 * @method \Spryker\Zed\SalesStatistics\Persistence\SalesStatisticsRepositoryInterface getRepository()
 */
class SalesStatisticsPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function getSalesOrderQuery(): SpySalesOrderQuery
    {
        return $this->getProvidedDependency(SalesStatisticsDependencyProvider::PROPEL_SALES_ORDER_QUERY);
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function getSalesOrderItemQuery(): SpySalesOrderItemQuery
    {
        return $this->getProvidedDependency(SalesStatisticsDependencyProvider::PROPEL_SALES_ORDER_ITEM_QUERY);
    }

    /**
     * @return \Spryker\Zed\SalesStatistics\Persistence\Propel\Mapper\SalesStatisticsMapper
     */
    public function createSalesStatisticsMapper(): SalesStatisticsMapper
    {
        return new SalesStatisticsMapper();
    }
}
