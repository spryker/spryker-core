<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader;

interface ShipmentTypeStorageReaderInterface
{
    /**
     * @param list<int> $shipmentMethodIds
     *
     * @return array<int, \Generated\Shared\Transfer\ShipmentTypeStorageTransfer>
     */
    public function getApplicableShipmentTypeStorageTransfersIndexedByIdShipmentMethod(array $shipmentMethodIds): array;
}
