<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Mapper;

interface ShipmentMethodsMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethodTransfers
     * @param array $restShipmentMethodsAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer[]
     */
    public function mapShipmentMethodTransfersToRestShipmentMethodsAttributesTransfers(
        array $shipmentMethodTransfers,
        array $restShipmentMethodsAttributesTransfers
    ): array;
}
