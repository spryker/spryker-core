<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePointStorage\Communication\Plugin\ShipmentTypeStorage;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShipmentTypeStorageExtension\Dependency\Plugin\ShipmentTypeStorageExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ShipmentTypeServicePointStorage\ShipmentTypeServicePointStorageConfig getConfig()
 * @method \Spryker\Zed\ShipmentTypeServicePointStorage\Business\ShipmentTypeServicePointStorageFacadeInterface getFacade()
 */
class ServiceTypeShipmentTypeStorageExpanderPlugin extends AbstractPlugin implements ShipmentTypeStorageExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ShipmentTypeStorageTransfer.idShipmentType` to be set.
     * - Retrieves a shipment type service type collection by provided `ShipmentTypeStorageTransfer.idShipmentType`.
     * - Maps related `ServiceType.uuid` to `ShipmentTypeStorageTransfer.serviceType.uuid`.
     * - Returns expanded list of `ShipmentTypeStorageTransfer` objects with service type.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorageTransfers
     *
     * @return list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer>
     */
    public function expand(array $shipmentTypeStorageTransfers): array
    {
        return $this->getFacade()->expandShipmentTypeStoragesWithServiceType($shipmentTypeStorageTransfers);
    }
}
