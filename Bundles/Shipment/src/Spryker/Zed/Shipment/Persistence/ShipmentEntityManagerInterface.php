<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentPersistenceFactory getFactory()
 */
interface ShipmentEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer|null $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function createSalesShipment(
        ShipmentTransfer $shipmentTransfer,
        OrderTransfer $orderTransfer,
        ?ExpenseTransfer $expenseTransfer = null
    ): ShipmentTransfer;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return void
     */
    public function updateFkShipmentForOrderItem(ItemTransfer $itemTransfer, ShipmentTransfer $shipmentTransfer): void ;
}
