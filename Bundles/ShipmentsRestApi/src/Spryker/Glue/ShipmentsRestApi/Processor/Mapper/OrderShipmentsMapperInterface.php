<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Mapper;

use ArrayObject;

interface OrderShipmentsMapperInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupsTransfers
     * @param \Generated\Shared\Transfer\RestOrderShipmentsAttributesTransfer[] $restOrderShipmentsAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestOrderShipmentsAttributesTransfer[]
     */
    public function mapShipmentGroupsTransfersToRestOrderShipmentsAttributesTransfer(
        ArrayObject $shipmentGroupsTransfers,
        array $restOrderShipmentsAttributesTransfers = []
    ): array;
}
