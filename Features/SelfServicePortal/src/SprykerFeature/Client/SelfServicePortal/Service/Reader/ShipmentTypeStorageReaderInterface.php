<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Service\Reader;

use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;

interface ShipmentTypeStorageReaderInterface
{
    /**
     * @param list<string> $shipmentTypeUuids
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    public function getShipmentTypeStorageCollection(array $shipmentTypeUuids): ShipmentTypeStorageCollectionTransfer;
}
