<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesRestApi\Processor\Sorter;

use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;

interface ShipmentTypeSorterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
     * @param list<\Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface> $sorts
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    public function sortShipmentTypeStorageCollection(
        ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer,
        array $sorts
    ): ShipmentTypeStorageCollectionTransfer;
}
