<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Persistence;

use Generated\Shared\Transfer\ChartDataTraceTransfer;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\SalesStatistics\Persistence\SalesStatisticsPersistenceFactory getFactory()
 */
class SalesStatisticsRepository extends AbstractRepository implements SalesStatisticsRepositoryInterface
{
    public const COUNT = 'count';
    public const DATE = 'date';
    public const STATUS_NAME = 'status_name';
    public const TOTAL = 'total';
    public const ITEM_NAME = 'item_name';
    public const ITEM_SKU = 'sku';

    /**
     * @param int $day
     *
     * @return \Generated\Shared\Transfer\ChartDataTraceTransfer
     */
    public function getOrderCountStatisticByDays(int $day): ChartDataTraceTransfer
    {
        $date = date('Y-m-d H:i:s.u', strtotime(sprintf('-%d days', $day)));

        $result = $this->getDataOrderCountStatisticByDays($date);

        return $this->getFactory()->createSalesStatisticsMapper()->mapCountStatisticToTransfer($result);
    }

    /**
     * @return \Generated\Shared\Transfer\ChartDataTraceTransfer
     */
    public function getStatusOrderStatistic(): ChartDataTraceTransfer
    {
        $result = $this->getDataStatusOrderStatistic();

        return $this->getFactory()->createSalesStatisticsMapper()->mapStatusOrderStatisticToTransfer($result);
    }

    /**
     * @param int $countProduct
     *
     * @return \Generated\Shared\Transfer\ChartDataTraceTransfer
     */
    public function getTopOrderStatistic(int $countProduct): ChartDataTraceTransfer
    {
        $result = $this->getDataTopOrderStatistic($countProduct);

        return $this->getFactory()->createSalesStatisticsMapper()->mapTopOrderStatisticToTransfer($result);
    }

    /**
     * @module Sales
     *
     * @example
     * [
     *  [
     *   'item_name' => 'exported'
     *   'count' => 1
     *  ],
     * ]
     *
     * @param int $countProduct
     *
     * @return array
     */
    protected function getDataTopOrderStatistic(int $countProduct): array
    {
        return $this->getFactory()->createSalesOrderItemQuery()
            ->select([static::ITEM_NAME, static::ITEM_SKU, static::COUNT])
            ->withColumn('COUNT(' . SpySalesOrderItemTableMap::COL_NAME . ')', static::COUNT)
            ->withColumn(SpySalesOrderItemTableMap::COL_NAME, static::ITEM_NAME)
            ->withColumn(SpySalesOrderItemTableMap::COL_SKU, static::ITEM_SKU)
            ->groupBy(SpySalesOrderItemTableMap::COL_NAME)
            ->limit($countProduct)
            ->orderBy(static::COUNT, Criteria::DESC)
            ->find()->toArray();
    }

    /**
     * @module Sales
     *
     * @example
     * [
     *  [
     *   'status_name' => 'exported'
     *   'total' => 1
     *  ],
     * ]
     *
     * @return array
     */
    protected function getDataStatusOrderStatistic(): array
    {
        return $this->getFactory()->createSalesOrderItemQuery()
            ->joinWithState()
            ->select([static::STATUS_NAME, static::TOTAL])
            ->withColumn(SpyOmsOrderItemStateTableMap::COL_NAME, static::STATUS_NAME)
            ->withColumn('SUM(' . SpySalesOrderItemTableMap::COL_PRICE_TO_PAY_AGGREGATION . ')', static::TOTAL)
            ->groupBy(SpyOmsOrderItemStateTableMap::COL_ID_OMS_ORDER_ITEM_STATE)
            ->find()->toArray();
    }

    /**
     * @module Sales
     *
     * @example
     * [
     *  [
     *   'date' => '2018-07-26'
     *   'count' => 1
     *  ],
     * ]
     *
     * @param string $date
     *
     * @return array
     */
    protected function getDataOrderCountStatisticByDays($date): array
    {
        return $this->getFactory()->createSalesOrderQuery()
            ->select([static::DATE, static::COUNT])
            ->withColumn('COUNT(' . SpySalesOrderTableMap::COL_ID_SALES_ORDER . ')', static::COUNT)
            ->withColumn('DATE(' . SpySalesOrderTableMap::COL_CREATED_AT . ')', static::DATE)
            ->where(sprintf("%s>='%s'", SpySalesOrderTableMap::COL_CREATED_AT, $date))
            ->groupBy(static::DATE)
            ->find()->toArray();
    }
}
