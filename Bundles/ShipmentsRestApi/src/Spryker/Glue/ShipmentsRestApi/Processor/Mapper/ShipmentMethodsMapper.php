<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer;

class ShipmentMethodsMapper implements ShipmentMethodsMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethodTransfers
     * @param RestShipmentMethodsAttributesTransfer[] $restShipmentMethodsAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer[]
     */
    public function mapShipmentMethodTransfersToRestShipmentMethodsAttributesTransfers(
        array $shipmentMethodTransfers,
        array $restShipmentMethodsAttributesTransfers
    ): array {
        foreach ($shipmentMethodTransfers as $shipmentMethodTransfer) {
            $idShipmentMethod = $shipmentMethodTransfer->getIdShipmentMethod();
            $restShipmentMethodsAttributesTransfer = (new RestShipmentMethodsAttributesTransfer())
                ->fromArray($shipmentMethodTransfer->toArray(), true);
            $restShipmentMethodsAttributesTransfers[$idShipmentMethod] = $restShipmentMethodsAttributesTransfer;
        }

        return $restShipmentMethodsAttributesTransfers;
    }
}
