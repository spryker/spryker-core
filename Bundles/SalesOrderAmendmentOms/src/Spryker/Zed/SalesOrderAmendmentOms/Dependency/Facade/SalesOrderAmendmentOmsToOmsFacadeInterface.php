<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade;

use Generated\Shared\Transfer\OmsOrderItemStateTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;

interface SalesOrderAmendmentOmsToOmsFacadeInterface
{
    /**
     * @param string $eventId
     * @param list<int> $orderItemIds
     * @param array<string, mixed> $data
     *
     * @return array<mixed>|null
     */
    public function triggerEventForOrderItems(string $eventId, array $orderItemIds, array $data = []): ?array;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $flag
     *
     * @return bool
     */
    public function areOrderItemsSatisfiedByFlag(OrderTransfer $orderTransfer, string $flag): bool;

    /**
     * @param string $stateName
     *
     * @return \Generated\Shared\Transfer\OmsOrderItemStateTransfer
     */
    public function getOmsOrderItemState(string $stateName): OmsOrderItemStateTransfer;

    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function updateReservation(ReservationRequestTransfer $reservationRequestTransfer): void;
}
