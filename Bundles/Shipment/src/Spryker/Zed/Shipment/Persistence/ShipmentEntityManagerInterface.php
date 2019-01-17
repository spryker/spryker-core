<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentPersistenceFactory getFactory()
 */
interface ShipmentEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return int
     */
    public function createSalesShipment(ShipmentTransfer $shipmentTransfer, int $idSalesOrder): int;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function updateSalesOrderItemFkShipment(ItemTransfer $itemTransfer, int $idSalesOrder): void;
}
