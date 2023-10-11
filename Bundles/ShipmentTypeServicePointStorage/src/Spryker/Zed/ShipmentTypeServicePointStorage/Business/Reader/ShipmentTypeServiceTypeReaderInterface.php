<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePointStorage\Business\Reader;

use Generated\Shared\Transfer\ShipmentTypeServiceTypeCollectionTransfer;

interface ShipmentTypeServiceTypeReaderInterface
{
    /**
     * @param list<int> $shipmentTypeIds
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeServiceTypeCollectionTransfer
     */
    public function getShipmentTypeServiceTypeCollection(array $shipmentTypeIds): ShipmentTypeServiceTypeCollectionTransfer;
}
