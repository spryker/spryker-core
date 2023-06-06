<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Business\Mapper;

use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;

class ShipmentTypeStorageMapper implements ShipmentTypeStorageMapperInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     * @param list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorageTransfers
     *
     * @return list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer>
     */
    public function mapShipmentTypeTransfersToShipmentTypeStorageTransfers(
        array $shipmentTypeTransfers,
        array $shipmentTypeStorageTransfers
    ): array {
        foreach ($shipmentTypeTransfers as $shipmentTypeTransfer) {
            $shipmentTypeStorageTransfers[] = $this->mapShipmentTypeTransferToShipmentTypeStorageTransfer(
                $shipmentTypeTransfer,
                new ShipmentTypeStorageTransfer(),
            );
        }

        return $shipmentTypeStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageTransfer
     */
    protected function mapShipmentTypeTransferToShipmentTypeStorageTransfer(
        ShipmentTypeTransfer $shipmentTypeTransfer,
        ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer
    ): ShipmentTypeStorageTransfer {
        return $shipmentTypeStorageTransfer->fromArray($shipmentTypeTransfer->toArray(), true);
    }
}
