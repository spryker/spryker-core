<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferPackagingUnit\Persistence;

use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateTableMap;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderProcessTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductOfferPackagingUnit\Persistence\ProductOfferPackagingUnitPersistenceFactory getFactory()
 */
class ProductOfferPackagingUnitRepository extends AbstractRepository implements ProductOfferPackagingUnitRepositoryInterface
{
    /**
     * @module Oms
     * @module Sales
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    public function getAggregatedReservations(ReservationRequestTransfer $reservationRequestTransfer): array
    {
        $reservationRequestTransfer->requireSku();
        $reservationRequestTransfer->requireReservedStates();

        $salesOrderItemQuery = $this->getFactory()
            ->getSalesOrderItemPropelQuery()
            ->filterByProductOfferReference($reservationRequestTransfer->getProductOfferReference())
            ->groupByAmountSku()
            ->groupByProductOfferReference()
            ->useStateQuery()
                ->filterByName_In(array_keys($reservationRequestTransfer->getReservedStates()->getStates()->getArrayCopy()))
            ->endUse()
            ->groupByFkOmsOrderItemState()
            ->innerJoinProcess()
            ->groupByFkOmsOrderProcess()
            ->select([SpySalesOrderItemTableMap::COL_SKU])
            ->withColumn(SpySalesOrderItemTableMap::COL_SKU, SalesOrderItemStateAggregationTransfer::SKU)
            ->withColumn(SpyOmsOrderProcessTableMap::COL_NAME, SalesOrderItemStateAggregationTransfer::PROCESS_NAME)
            ->withColumn(SpyOmsOrderItemStateTableMap::COL_NAME, SalesOrderItemStateAggregationTransfer::STATE_NAME)
            ->withColumn(
                sprintf(
                    "CASE WHEN %s = '%s' THEN SUM(%s) ELSE SUM(%s) END",
                    SpySalesOrderItemTableMap::COL_AMOUNT_SKU,
                    $reservationRequestTransfer->getSku(),
                    SpySalesOrderItemTableMap::COL_AMOUNT,
                    SpySalesOrderItemTableMap::COL_QUANTITY
                ),
                SalesOrderItemStateAggregationTransfer::SUM_AMOUNT
            );
            $salesOrderItemQuery = $salesOrderItemQuery->condition('sku', 'spy_sales_order_item.sku = ?', $reservationRequestTransfer->getSku())
                ->condition('amount_sku', 'spy_sales_order_item.amount_sku = ?', $reservationRequestTransfer->getSku())
                ->where(['sku', 'amount_sku'], Criteria::LOGICAL_OR);

        if ($reservationRequestTransfer->getStore() !== null) {
            $salesOrderItemQuery
                ->useOrderQuery()
                    ->filterByStore($reservationRequestTransfer->getStore()->getName())
                ->endUse();
        }

        $salesAggregationTransfers = [];
        /**
         * @var array $salesOrderItemAggregation
         */
        foreach ($salesOrderItemQuery->find() as $salesOrderItemAggregation) {
            $salesAggregationTransfers[] = (new SalesOrderItemStateAggregationTransfer())
                ->fromArray($salesOrderItemAggregation, true);
        }

        return $salesAggregationTransfers;
    }
}
