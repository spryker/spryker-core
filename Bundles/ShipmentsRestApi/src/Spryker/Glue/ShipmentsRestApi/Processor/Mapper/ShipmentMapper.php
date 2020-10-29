<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestAddressTransfer;
use Generated\Shared\Transfer\RestShipmentMethodTransfer;
use Generated\Shared\Transfer\RestShipmentsAttributesTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;

class ShipmentMapper implements ShipmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\RestShipmentsAttributesTransfer $restShipmentsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestShipmentsAttributesTransfer
     */
    public function mapShipmentGroupTransferToRestShipmentsAttributesTransfers(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        RestShipmentsAttributesTransfer $restShipmentsAttributesTransfer
    ): RestShipmentsAttributesTransfer {
        $restShipmentsAttributesTransfer
            ->setItems($this->getItemsGroupKeys($shipmentGroupTransfer))
            ->setShippingAddress(
                (new RestAddressTransfer())->fromArray(
                    $shipmentGroupTransfer->getShipment()->getShippingAddress()->toArray(),
                    true
                )
            )
            ->setSelectedShipmentMethod(
                (new RestShipmentMethodTransfer())->fromArray(
                    $shipmentGroupTransfer->getShipment()->getMethod()->toArray(),
                    true
                )
            )
            ->setRequestedDeliveryDate($shipmentGroupTransfer->getShipment()->getRequestedDeliveryDate());

        return $restShipmentsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return string[]
     */
    protected function getItemsGroupKeys(ShipmentGroupTransfer $shipmentGroupTransfer): array
    {
        $groupKeys = [];
        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
            $groupKeys[] = $itemTransfer->getGroupKey();
        }

        return $groupKeys;
    }
}
