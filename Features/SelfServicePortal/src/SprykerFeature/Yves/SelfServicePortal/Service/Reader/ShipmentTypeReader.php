<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Reader;

use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCriteriaTransfer;
use Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageClientInterface;
use SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig;

class ShipmentTypeReader implements ShipmentTypeReaderInterface
{
    public function __construct(
        protected ShipmentTypeStorageClientInterface $shipmentTypeStorageClient,
        protected SelfServicePortalConfig $SelfServicePortalConfig
    ) {
    }

    /**
     * @param list<string> $shipmentTypeUuids
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    public function getShipmentTypeStorageCollection(array $shipmentTypeUuids, string $storeName): ShipmentTypeStorageCollectionTransfer
    {
        if ($shipmentTypeUuids === []) {
            return new ShipmentTypeStorageCollectionTransfer();
        }

        $shipmentTypeStorageCriteriaTransfer = (new ShipmentTypeStorageCriteriaTransfer())
            ->setShipmentTypeStorageConditions(
                (new ShipmentTypeStorageConditionsTransfer())
                    ->setUuids($shipmentTypeUuids)
                    ->setStoreName($storeName),
            );

        return $this->shipmentTypeStorageClient
            ->getShipmentTypeStorageCollection($shipmentTypeStorageCriteriaTransfer);
    }
}
