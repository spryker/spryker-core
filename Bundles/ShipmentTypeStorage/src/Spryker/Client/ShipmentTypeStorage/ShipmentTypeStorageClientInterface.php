<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeStorage;

use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCriteriaTransfer;

interface ShipmentTypeStorageClientInterface
{
    /**
     * Specification:
     * - Requires `ShipmentTypeStorageCriteriaTransfer.shipmentTypeStorageConditions` to be set.
     * - Requires `ShipmentTypeStorageCriteriaTransfer.shipmentTypeStorageConditions.storeName` to be set.
     * - Retrieves shipment type storage data filtered by criteria from Storage.
     * - Uses `ShipmentTypeStorageCriteriaTransfer.shipmentTypeStorageConditions.shipmentTypeIds` to filter by shipment type IDs.
     * - Uses `ShipmentTypeStorageCriteriaTransfer.shipmentTypeStorageConditions.uuids` to filter by shipment type uuids.
     * - Can filter either by `shipmentTypeIds` or `uuids` at the same time.
     * - Returns `ShipmentTypeStorageCollectionTransfer` filled with found shipment types.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCriteriaTransfer $shipmentTypeStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    public function getShipmentTypeStorageCollection(
        ShipmentTypeStorageCriteriaTransfer $shipmentTypeStorageCriteriaTransfer
    ): ShipmentTypeStorageCollectionTransfer;
}
