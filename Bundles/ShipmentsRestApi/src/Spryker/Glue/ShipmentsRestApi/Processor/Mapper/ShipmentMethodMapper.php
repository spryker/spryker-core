<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer;

class ShipmentMethodMapper implements ShipmentMethodMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethodTransfers
     * @param \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer[] $restShipmentMethodsAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer[]
     */
    public function mapShipmentMethodTransfersToRestShipmentMethodsAttributesTransfers(
        array $shipmentMethodTransfers,
        array $restShipmentMethodsAttributesTransfers = []
    ): array {
        foreach ($shipmentMethodTransfers as $shipmentMethodTransfer) {
            $restShipmentMethodsAttributesTransfer = (new RestShipmentMethodsAttributesTransfer())
                ->fromArray($shipmentMethodTransfer->toArray(), true);

            $moneyValueTransfer = current($shipmentMethodTransfer->getPrices());

            if (!$moneyValueTransfer) {
                continue;
            }

            $restShipmentMethodsAttributesTransfer->setDefaultGrossPrice($moneyValueTransfer->getGrossAmount());
            $restShipmentMethodsAttributesTransfer->setDefaultNetPrice($moneyValueTransfer->getNetAmount());

            $restShipmentMethodsAttributesTransfers[$shipmentMethodTransfer->getIdShipmentMethod()] = $restShipmentMethodsAttributesTransfer;
        }

        return $restShipmentMethodsAttributesTransfers;
    }
}
