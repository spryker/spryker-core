<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeStorageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;

/**
 * Provides ability to expand shipment type storage collection with additional data after retrieving it from the storage.
 */
interface ShipmentTypeStorageExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands shipment type storage collection with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    public function expand(ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer): ShipmentTypeStorageCollectionTransfer;
}
