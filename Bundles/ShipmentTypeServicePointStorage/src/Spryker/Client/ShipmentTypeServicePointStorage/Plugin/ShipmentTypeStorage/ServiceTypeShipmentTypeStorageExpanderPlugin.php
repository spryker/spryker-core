<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeServicePointStorage\Plugin\ShipmentTypeStorage;

use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ShipmentTypeStorageExtension\Dependency\Plugin\ShipmentTypeStorageExpanderPluginInterface;

/**
 * @method \Spryker\Client\ShipmentTypeServicePointStorage\ShipmentTypeServicePointStorageClientInterface getClient()
 */
class ServiceTypeShipmentTypeStorageExpanderPlugin extends AbstractPlugin implements ShipmentTypeStorageExpanderPluginInterface
{
    /**
     * {@inheritDoc}
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
    public function expand(ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer): ShipmentTypeStorageCollectionTransfer
    {
        return $this->getClient()
            ->expandShipmentTypeStorageCollectionWithServiceType($shipmentTypeStorageCollectionTransfer);
    }
}
