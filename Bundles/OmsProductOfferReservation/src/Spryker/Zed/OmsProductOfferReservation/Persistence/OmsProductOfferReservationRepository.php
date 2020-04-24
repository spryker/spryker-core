<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsProductOfferReservation\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer;
use Generated\Shared\Transfer\OmsProductOfferReservationTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
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
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OmsProductOfferReservationTransfer|null
     */
    public function find(
        ReservationRequestTransfer $reservationRequestTransfer
    ): ?OmsProductOfferReservationTransfer {
        $reservationRequestTransfer->requireProductOfferReference()
            ->requireReservationQuantity()
            ->requireStore()
            ->getStore()
            ->requireIdStore();

        $omsProductOfferReservationEntity = $this->getFactory()->getOmsProductOfferReservationPropelQuery()
            ->filterByProductOfferReference($reservationRequestTransfer->getProductOfferReference())
            ->filterByFkStore($reservationRequestTransfer->getStore()->getIdStore())
            ->findOne();

        if (!$omsProductOfferReservationEntity) {
            return null;
        }

        return $this->getFactory()
            ->createOmsProductOfferReservationMapper()
            ->mapOmsProductOfferReservationEntityToOmsProductOfferReservationTransfer(
                $omsProductOfferReservationEntity,
                new OmsProductOfferReservationTransfer()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\OmsProductOfferReservationCriteriaTransfer $omsProductOfferReservationCriteriaTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getQuantity(
        OmsProductOfferReservationCriteriaTransfer $omsProductOfferReservationCriteriaTransfer
    ): Decimal {
        $quantity = $this->getFactory()->getOmsProductOfferReservationPropelQuery()
            ->filterByProductOfferReference($omsProductOfferReservationCriteriaTransfer->getProductOfferReference())
            ->filterByFkStore($omsProductOfferReservationCriteriaTransfer->getIdStore())
            ->select([SpyOmsProductOfferReservationTableMap::COL_RESERVATION_QUANTITY])
            ->findOne();

        if (!$quantity) {
            return new Decimal(0);
        }

        return new Decimal($quantity);
    }

    /**
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
        $salesOrderItemQuery
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
