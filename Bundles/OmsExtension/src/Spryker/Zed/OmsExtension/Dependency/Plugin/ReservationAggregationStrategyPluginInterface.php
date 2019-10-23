<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OmsStateCollectionTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface ReservationAggregationStrategyPluginInterface
{
    /**
     * Specification:
     * - Aggregates sales order items reservations for a given sku, in the given states, and store.
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\OmsStateCollectionTransfer $reservedStates
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    public function aggregateSalesOrderItemReservations(string $sku, OmsStateCollectionTransfer $reservedStates, StoreTransfer $storeTransfer): array;
}
