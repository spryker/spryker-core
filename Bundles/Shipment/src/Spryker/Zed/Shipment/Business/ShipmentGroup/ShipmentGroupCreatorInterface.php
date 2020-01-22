<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentGroup;

use Generated\Shared\Transfer\ShipmentGroupTransfer;

interface ShipmentGroupCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param bool[] $itemListUpdatedStatus
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    public function createShipmentGroupTransferWithListedItems(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        array $itemListUpdatedStatus
    ): ShipmentGroupTransfer;
}
