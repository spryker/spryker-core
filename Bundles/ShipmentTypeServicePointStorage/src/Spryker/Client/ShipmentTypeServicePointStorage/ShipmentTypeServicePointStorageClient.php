<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeServicePointStorage;

use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ShipmentTypeServicePointStorage\ShipmentTypeServicePointStorageFactory getFactory()
 */
class ShipmentTypeServicePointStorageClient extends AbstractClient implements ShipmentTypeServicePointStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    public function expandShipmentTypeStorageCollectionWithServiceType(
        ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
    ): ShipmentTypeStorageCollectionTransfer {
        return $this->getFactory()
            ->createServiceTypeExpander()
            ->expandShipmentTypeStorageCollectionWithServiceType($shipmentTypeStorageCollectionTransfer);
    }
}
