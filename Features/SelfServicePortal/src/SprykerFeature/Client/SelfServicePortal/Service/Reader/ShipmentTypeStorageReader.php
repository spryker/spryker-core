<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Service\Reader;

use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCriteriaTransfer;
use Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;

class ShipmentTypeStorageReader implements ShipmentTypeStorageReaderInterface
{
    public function __construct(
        protected ShipmentTypeStorageClientInterface $shipmentTypeStorageClient,
        protected StoreClientInterface $storeClient
    ) {
    }

    /**
     * @param list<string> $shipmentTypeUuids
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    public function getShipmentTypeStorageCollection(array $shipmentTypeUuids): ShipmentTypeStorageCollectionTransfer
    {
        $shipmentTypeStorageConditionsTransfer = (new ShipmentTypeStorageConditionsTransfer())
            ->setUuids($shipmentTypeUuids)
            ->setStoreName($this->storeClient->getCurrentStore()->getName());

        $shipmentTypeStorageCriteriaTransfer = (new ShipmentTypeStorageCriteriaTransfer())
            ->setShipmentTypeStorageConditions($shipmentTypeStorageConditionsTransfer);

        return $this->shipmentTypeStorageClient
            ->getShipmentTypeStorageCollection($shipmentTypeStorageCriteriaTransfer);
    }
}
