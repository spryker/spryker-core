<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\StoreTransfer;

interface ShipmentMethodMapperInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethodTransfers
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer[]
     */
    public function mapShipmentMethodTransfersToRestShipmentMethodAttributesTransfers(
        ArrayObject $shipmentMethodTransfers,
        StoreTransfer $storeTransfer
    ): array;
}
