<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Persistence;

use Generated\Shared\Transfer\SalesStatisticTransfer;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesStatistics\Persistence\SalesStatisticsPersistenceFactory getFactory()
 */
class SalesStatisticsRepository extends AbstractRepository implements SalesStatisticsRepositoryInterface
{
    public const COUNT = 'count';
    public const DATE = 'date';
    public const STATUS_NAME = 'status_name';
    public const TOTAL = 'total';

    /**
     * @param int $day
     *
     * @return \Generated\Shared\Transfer\SalesStatisticTransfer
     */
    public function getOrderCountStatisticByDays($day): SalesStatisticTransfer
    {
        $dateInterval = date('Y-m-d H:i:s.u', strtotime(sprintf('-%d days', $day)));

        $result = $this->getFactory()->createSalesOrderQuery()
            ->select([static::DATE, static::DATE])
            ->withColumn('COUNT(' . SpySalesOrderTableMap::COL_ID_SALES_ORDER . ')', static::COUNT)
            ->withColumn('TO_CHAR(' . SpySalesOrderTableMap::COL_CREATED_AT . ', \'yyyy-mm-dd\')', static::DATE)
            ->where(sprintf("%s>='%s'", SpySalesOrderTableMap::COL_CREATED_AT, $dateInterval))
            ->groupBy(static::DATE)
            ->find()->toArray();

        return $this->getFactory()->createSalesStatisticsMapper()->mapCountStatisticToEntityTransfer($result);
    }

    /**
     * @return \Generated\Shared\Transfer\SalesStatisticTransfer
     */
    public function getStatusOrderStatistic(): SalesStatisticTransfer
    {
        $result = $this->getFactory()->createSalesOrderItemQuery()
            ->joinWithState()
            ->select([static::STATUS_NAME, static::TOTAL])
            ->withColumn('SUM(' . SpySalesOrderItemTableMap::COL_PRICE_TO_PAY_AGGREGATION . ')', static::TOTAL)
            ->withColumn(SpyOmsOrderItemStateTableMap::COL_NAME, static::STATUS_NAME)
            ->groupBy(SpyOmsOrderItemStateTableMap::COL_ID_OMS_ORDER_ITEM_STATE)
            ->find()->toArray();

        return $this->getFactory()->createSalesStatisticsMapper()->mapStatusOrderStatisticToEntityTransfer($result);
    }
}
