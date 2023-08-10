<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeCart\Business\Reader;

use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;

interface ShipmentTypeReaderInterface
{
    /**
     * @param list<string> $shipmentTypeUuids
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer
     */
    public function getActiveShipmentTypeCollection(array $shipmentTypeUuids, string $storeName): ShipmentTypeCollectionTransfer;
}
