<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer;
use Generated\Shared\Transfer\ReservationResponseTransfer;
use Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateTableMap;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderProcessTableMap;
use Orm\Zed\OmsProductOfferReservation\Persistence\Map\SpyOmsProductOfferReservationTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\OmsProductOfferReservation\Persistence\OmsProductOfferReservationPersistenceFactory getFactory()
 */
class OmsProductOfferReservationRepository extends AbstractRepository implements OmsProductOfferReservationRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer $omsProductOfferReservationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ReservationResponseTransfer
     */
    public function getQuantity(
        OmsProductOfferReservationCriteriaTransfer $omsProductOfferReservationCriteriaTransfer
    ): ReservationResponseTransfer {
        $omsProductOfferReservationCriteriaTransfer->requireProductOfferReference();
        $omsProductOfferReservationCriteriaTransfer->requireIdStore();

        $quantity = $this->getFactory()->createPropelOmsProductOfferReservationQuery()
            ->filterByProductOfferReference($omsProductOfferReservationCriteriaTransfer->getProductOfferReference())
            ->filterByFkStore($omsProductOfferReservationCriteriaTransfer->getIdStore())
            ->select([SpyOmsProductOfferReservationTableMap::COL_RESERVATION_QUANTITY])
            ->findOne();

        $reservationResponseTransfer = new ReservationResponseTransfer();

        if (!$quantity) {
            return $reservationResponseTransfer->setReservationQuantity(new Decimal(0));
        }

        return $reservationResponseTransfer->setReservationQuantity(new Decimal($quantity));
    }

    /**
     * @TODO Needs to check it without filter by sku. If all is ok just remove this TODO.
     *
     * @module Oms
     * @module Sales
     *
     * @param string $productOfferReference
     * @param \ArrayObject|\Generated\Shared\Transfer\OmsStateTransfer[] $omsStateTransfers
     * @param \Spryker\Zed\OmsProductOfferReservation\Persistence\StoreTransfer|null $storeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    public function getAggregatedReservations(
        string $productOfferReference,
        ArrayObject $omsStateTransfers,
        ?StoreTransfer $storeTransfer = null
    ): array {
        $salesOrderItemQuery = (new SpySalesOrderItemQuery())
            ->filterByProductOfferReference($productOfferReference)
            ->useStateQuery()
                ->filterByName_In(array_keys($omsStateTransfers->getArrayCopy()))
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

        if ($storeTransfer !== null) {
            $salesOrderItemQuery
                ->useOrderQuery()
                    ->filterByStore($storeTransfer->getName())
                ->endUse();
        }

        $salesAggregationTransfers = [];
        foreach ($salesOrderItemQuery->find() as $salesOrderItemAggregation) {
            $salesAggregationTransfers[] = (new SalesOrderItemStateAggregationTransfer())
                ->fromArray($salesOrderItemAggregation, true);
        }

        return $salesAggregationTransfers;
    }
}
