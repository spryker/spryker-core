<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Extractor;

use ArrayObject;

interface ShipmentTypeExtractorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return list<string>
     */
    public function extractShipmentTypeUuidsFromShipmentTypeTransfers(ArrayObject $shipmentTypeTransfers): array;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return list<int>
     */
    public function extractShipmentTypeIdsFromShipmentTypeTransfers(ArrayObject $shipmentTypeTransfers): array;
}
