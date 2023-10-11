<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeServicePointStorage;

use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;

interface ShipmentTypeServicePointStorageClientInterface
{
    /**
     * Specification:
     * - Expects `ShipmentTypeStorageCollectionTransfer.shipmentType.serviceType.uuid` transfer property to be set.
     * - Retrieves service type data from storage by provided `ShipmentTypeStorageCollectionTransfer.shipmentType.serviceType.uuid` data.
     * - Returns `ShipmentTypeStorageCollectionTransfer` expanded with service types.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    public function expandShipmentTypeStorageCollectionWithServiceType(
        ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
    ): ShipmentTypeStorageCollectionTransfer;
}
