<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client;

use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCriteriaTransfer;

class ShipmentTypeServicePointsRestApiToShipmentTypeStorageClientBridge implements ShipmentTypeServicePointsRestApiToShipmentTypeStorageClientInterface
{
    /**
     * @var \Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageClientInterface
     */
    protected $shipmentTypeStorageClient;

    /**
     * @param \Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageClientInterface $shipmentTypeStorageClient
     */
    public function __construct($shipmentTypeStorageClient)
    {
        $this->shipmentTypeStorageClient = $shipmentTypeStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCriteriaTransfer $shipmentTypeStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    public function getShipmentTypeStorageCollection(
        ShipmentTypeStorageCriteriaTransfer $shipmentTypeStorageCriteriaTransfer
    ): ShipmentTypeStorageCollectionTransfer {
        return $this->shipmentTypeStorageClient->getShipmentTypeStorageCollection($shipmentTypeStorageCriteriaTransfer);
    }
}
