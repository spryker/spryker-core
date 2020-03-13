<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Reservation;

use Generated\Shared\Transfer\OmsStateCollectionTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface ProductPackagingUnitReservationHandlerInterface
{
    /**
     * @param string $sku
     *
     * @return void
     */
    public function updateLeadProductReservation(string $sku): void;

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\OmsStateCollectionTransfer $reservedStates
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    public function aggregateProductPackagingUnitReservation(
        string $sku,
        OmsStateCollectionTransfer $reservedStates,
        ?StoreTransfer $storeTransfer = null
    ): array;
}
