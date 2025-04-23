<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence;

use Generated\Shared\Transfer\ReservationRequestTransfer;

interface OmsEntityManagerInterface
{
    /**
     * @deprecated use {@link \Spryker\Zed\Oms\Persistence\OmsEntityManager::saveReservation()} instead
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function createReservation(ReservationRequestTransfer $reservationRequestTransfer): void;

    /**
     * @deprecated use {@link \Spryker\Zed\Oms\Persistence\OmsEntityManager::saveReservation()} instead
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function updateReservation(ReservationRequestTransfer $reservationRequestTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function saveReservation(ReservationRequestTransfer $reservationRequestTransfer): void;

    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return void
     */
    public function deleteOmsOrderItemStateHistoryBySalesOrderItemIds(array $salesOrderItemIds): void;

    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return void
     */
    public function deleteOmsTransitionLogsBySalesOrderItemIds(array $salesOrderItemIds): void;

    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return void
     */
    public function deleteOmsEventTimeoutsBySalesOrderItemIds(array $salesOrderItemIds): void;
}
