<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence;

use Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateTableMap;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderProcessTableMap;
use Orm\Zed\Oms\Persistence\Map\SpyOmsProductReservationTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Oms\Persistence\OmsPersistenceFactory getFactory()
 */
class OmsRepository extends AbstractRepository implements OmsRepositoryInterface
{
    protected const COL_PRODUCT_RESERVATION_TOTAL_QUANTITY = 'productReservationTotalQuantity';

    /**
     * @param int[] $processIds
     * @param int[] $stateBlackList
     *
     * @return array
     */
    public function getMatrixOrderItems(array $processIds, array $stateBlackList): array
    {
        $orderItemsMatrixResult = $this->getFactory()->getOmsQueryContainer()
            ->queryGroupedMatrixOrderItems($processIds, $stateBlackList)
            ->find();

        return $this->getFactory()
            ->createOrderItemMapper()
            ->mapOrderItemMatrix($orderItemsMatrixResult->getArrayCopy());
    }

    /**
     * @param string[] $stateNames
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    public function getSalesOrderAggregationBySkuAndStatesNames(array $stateNames, string $sku, StoreTransfer $storeTransfer): array
    {
        $salesOrderItemQuery = $this->getFactory()
            ->getSalesQueryContainer()
            ->querySalesOrderItem()
            ->filterBySku($sku)
            ->useStateQuery()
                ->filterByName_In($stateNames)
            ->endUse()
            ->useOrderQuery()
                ->filterByStore($storeTransfer->getName())
            ->endUse()
            ->groupByFkOmsOrderItemState()
            ->innerJoinProcess()
            ->groupByFkOmsOrderProcess()
            ->withColumn(SpySalesOrderItemTableMap::COL_SKU, SalesOrderItemStateAggregationTransfer::SKU)
            ->withColumn(SpyOmsOrderProcessTableMap::COL_NAME, SalesOrderItemStateAggregationTransfer::PROCESS_NAME)
            ->withColumn(SpyOmsOrderItemStateTableMap::COL_NAME, SalesOrderItemStateAggregationTransfer::STATE_NAME)
            ->withColumn('SUM(' . SpySalesOrderItemTableMap::COL_QUANTITY . ')', SalesOrderItemStateAggregationTransfer::SUM_AMOUNT)
            ->select([
                SpySalesOrderItemTableMap::COL_SKU,
            ]);

        $salesAggregationTransfers = [];
        foreach ($salesOrderItemQuery->find() as $salesOrderItemAggregation) {
            $salesAggregationTransfers[] = (new SalesOrderItemStateAggregationTransfer())
                ->fromArray($salesOrderItemAggregation, true);
        }

        return $salesAggregationTransfers;
    }

    /**
     * @param string[] $concreteSkus
     * @param int $idStore
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getSumOmsReservedProductQuantityByConcreteProductSkusForStore(array $concreteSkus, int $idStore): Decimal
    {
        $productReservationTotalQuantity = $this->getFactory()
            ->createOmsProductReservationQuery()
            ->select([static::COL_PRODUCT_RESERVATION_TOTAL_QUANTITY])
            ->filterByFkStore($idStore)
            ->filterBySku_In($concreteSkus)
            ->withColumn(sprintf('SUM(%s)', SpyOmsProductReservationTableMap::COL_RESERVATION_QUANTITY), static::COL_PRODUCT_RESERVATION_TOTAL_QUANTITY)
            ->findOne();

        return new Decimal($productReservationTotalQuantity ?? 0);
    }
}
