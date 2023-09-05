<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeServicePoint;

use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ShipmentTypeServicePoint\ShipmentTypeServicePointFactory getFactory()
 */
class ShipmentTypeServicePointClient extends AbstractClient implements ShipmentTypeServicePointClientInterface
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
