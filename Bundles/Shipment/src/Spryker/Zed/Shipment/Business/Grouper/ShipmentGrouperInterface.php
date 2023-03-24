<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Grouper;

use Generated\Shared\Transfer\ShipmentCollectionTransfer;

interface ShipmentGrouperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentCollectionTransfer $shipmentCollectionTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ShipmentTransfer>
     */
    public function getShipmentTransferCollectionIndexedByIdSalesShipment(
        ShipmentCollectionTransfer $shipmentCollectionTransfer
    ): array;
}
