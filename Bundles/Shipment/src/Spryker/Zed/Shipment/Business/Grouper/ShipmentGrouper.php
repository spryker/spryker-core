<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Grouper;

use ArrayObject;

class ShipmentGrouper implements ShipmentGrouperInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTransfer> $shipmentTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ShipmentTransfer>
     */
    public function getShipmentTransfersIndexedByIdSalesShipment(ArrayObject $shipmentTransfers): array
    {
        $shipmentTransferCollectionIndexedByIdSalesShipment = [];
        foreach ($shipmentTransfers as $shipmentTransfer) {
            $idSalesShipment = $shipmentTransfer->getIdSalesShipment();
            if (!$idSalesShipment) {
                continue;
            }

            $shipmentTransferCollectionIndexedByIdSalesShipment[$idSalesShipment] = $shipmentTransfer;
        }

        return $shipmentTransferCollectionIndexedByIdSalesShipment;
    }
}
