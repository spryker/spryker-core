<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferPackagingUnit\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateTableMap;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderProcessTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductOfferPackagingUnit\Persistence\ProductOfferPackagingUnitPersistenceFactory getFactory()
 */
class ProductOfferPackagingUnitRepository extends AbstractRepository implements ProductOfferPackagingUnitRepositoryInterface
{
    /**
     * @TODO Needs to check it without filter by sku. If all is ok just remove this TODO.
     *
     * @module Oms
     * @module Sales
     *
     * @param string $productOfferReference
     * @param \ArrayObject|\Generated\Shared\Transfer\OmsStateTransfer[] $omsStateTransfers
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    public function getAggregatedReservations(
        string $productOfferReference,
        ArrayObject $omsStateTransfers,
        ?StoreTransfer $storeTransfer = null
    ): array {
        $salesOrderItemQuery = $this->getFactory()->getSalesOrderItemPropelQuery();
        $salesOrderItemQuery->filterByProductOfferReference($productOfferReference)
            ->groupByAmountSku()
            ->useStateQuery()
                ->filterByName_In(array_keys($omsStateTransfers->getArrayCopy()))
            ->endUse()
            ->groupByFkOmsOrderItemState()
            ->innerJoinProcess()
            ->groupByFkOmsOrderProcess()
            ->select([SpySalesOrderItemTableMap::COL_SKU])
            ->withColumn(SpyOmsOrderProcessTableMap::COL_NAME, SalesOrderItemStateAggregationTransfer::PROCESS_NAME)
            ->withColumn(SpyOmsOrderItemStateTableMap::COL_NAME, SalesOrderItemStateAggregationTransfer::STATE_NAME)
            ->withColumn(
                sprintf(
                    "CASE WHEN %s IS NOT NULL THEN SUM(%s) ELSE SUM(%s) END",
                    SpySalesOrderItemTableMap::COL_AMOUNT_SKU,
                    SpySalesOrderItemTableMap::COL_AMOUNT,
                    SpySalesOrderItemTableMap::COL_QUANTITY
                ),
                SalesOrderItemStateAggregationTransfer::SUM_AMOUNT
            );

        if ($storeTransfer !== null) {
            $salesOrderItemQuery
                ->useOrderQuery()
                    ->filterByStore($storeTransfer->getName())
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
