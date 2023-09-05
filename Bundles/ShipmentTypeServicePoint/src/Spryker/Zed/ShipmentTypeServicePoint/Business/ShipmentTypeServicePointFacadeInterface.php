<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePoint\Business;

interface ShipmentTypeServicePointFacadeInterface
{
    /**
     * Specification:
     * - Requires `ShipmentTypeStorageTransfer.idShipmentType` to be set.
     * - Retrieves service type IDs from Persistence by provided shipment type IDs.
     * - Retrieves service types.
     * - Maps related `ServiceType.uuid` to `ShipmentTypeStorageTransfer.serviceType.uuid`.
     * - Returns expanded list of `ShipmentTypeStorageTransfer` objects with service type.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorageTransfers
     *
     * @return list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer>
     */
    public function expandShipmentTypeStoragesWithServiceType(array $shipmentTypeStorageTransfers): array;
}
