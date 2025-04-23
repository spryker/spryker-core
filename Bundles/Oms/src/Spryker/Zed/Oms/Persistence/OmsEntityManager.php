<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence;

use Generated\Shared\Transfer\ReservationRequestTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservation;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Oms\Persistence\OmsPersistenceFactory getFactory()
 */
class OmsEntityManager extends AbstractEntityManager implements OmsEntityManagerInterface
{
    /**
     * @deprecated use {@link \Spryker\Zed\Oms\Persistence\OmsEntityManager::saveReservation()} instead
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function createReservation(ReservationRequestTransfer $reservationRequestTransfer): void
    {
        $reservationRequestTransfer->requireSku();
        $reservationRequestTransfer->requireStore();

        $omsProductReservationEntity = $this->getFactory()
            ->createOmsMapper()
            ->mapReservationRequestTransferToOmsProductReservationEntity(
                $reservationRequestTransfer,
                new SpyOmsProductReservation(),
            );

        $omsProductReservationEntity->save();
    }

    /**
     * @deprecated use {@link \Spryker\Zed\Oms\Persistence\OmsEntityManager::saveReservation()} instead
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function updateReservation(ReservationRequestTransfer $reservationRequestTransfer): void
    {
        $reservationRequestTransfer->requireSku()
            ->requireStore()
            ->getStore()
                ->requireIdStore();

        $omsProductReservationEntity = $this->getFactory()
            ->createOmsProductReservationQuery()
            ->filterBySku($reservationRequestTransfer->getSku())
            ->filterByFkStore($reservationRequestTransfer->getStore()->getIdStore())
            ->findOne();

        $omsProductReservationEntity->setReservationQuantity($reservationRequestTransfer->getReservationQuantity());
        $omsProductReservationEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function saveReservation(ReservationRequestTransfer $reservationRequestTransfer): void
    {
        $reservationRequestTransfer->requireSku()
            ->requireStore()
            ->getStore()
                ->requireIdStore();

        $omsProductReservationEntity = $this->getFactory()
            ->createOmsProductReservationQuery()
            ->filterBySku($reservationRequestTransfer->getSku())
            ->filterByFkStore($reservationRequestTransfer->getStore()->getIdStore())
            ->findOneOrCreate();
        $omsProductReservationEntity->fromArray($reservationRequestTransfer->toArray());
        $omsProductReservationEntity->save();
    }

    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return void
     */
    public function deleteOmsOrderItemStateHistoryBySalesOrderItemIds(array $salesOrderItemIds): void
    {
        $this->getFactory()
            ->createOmsOrderItemStateHistoryQuery()
            ->filterByFkSalesOrderItem_In($salesOrderItemIds)
            ->delete();
    }

    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return void
     */
    public function deleteOmsTransitionLogsBySalesOrderItemIds(array $salesOrderItemIds): void
    {
        $this->getFactory()
            ->createOmsTransitionLogQuery()
            ->filterByFkSalesOrderItem_In($salesOrderItemIds)
            ->delete();
    }

    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return void
     */
    public function deleteOmsEventTimeoutsBySalesOrderItemIds(array $salesOrderItemIds): void
    {
        $this->getFactory()
            ->createOmsEventTimeoutQuery()
            ->filterByFkSalesOrderItem_In($salesOrderItemIds)
            ->delete();
    }
}
