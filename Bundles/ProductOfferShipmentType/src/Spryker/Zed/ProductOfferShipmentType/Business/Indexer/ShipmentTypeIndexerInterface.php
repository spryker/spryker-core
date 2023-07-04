<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Indexer;

use ArrayObject;

interface ShipmentTypeIndexerInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    public function getShipmentTypeTransfersIndexedByIdShipmentType(ArrayObject $shipmentTypeTransfers): array;
}
