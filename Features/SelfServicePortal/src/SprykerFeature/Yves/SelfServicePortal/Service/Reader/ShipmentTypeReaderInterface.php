<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Reader;

use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;

interface ShipmentTypeReaderInterface
{
    /**
     * @param list<string> $shipmentTypeUuids
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    public function getShipmentTypeStorageCollection(array $shipmentTypeUuids, string $storeName): ShipmentTypeStorageCollectionTransfer;
}
