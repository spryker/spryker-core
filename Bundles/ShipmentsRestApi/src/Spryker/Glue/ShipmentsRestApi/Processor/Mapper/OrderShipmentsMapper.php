<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RestOrderShipmentsAttributesTransfer;

class OrderShipmentsMapper implements OrderShipmentsMapperInterface
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
    ): array {
        foreach ($shipmentGroupsTransfers as $shipmentGroupTransfer) {
            $shipmentTransfer = $shipmentGroupTransfer->getShipment();
            $itemsTransfers = $shipmentGroupTransfer->getItems()->getArrayCopy();

            $restOrderShipmentsAttributesTransfer = (new RestOrderShipmentsAttributesTransfer())
                ->fromArray($shipmentGroupTransfer->toArray(), true)
                ->setItemUuids(
                    array_map(function (ItemTransfer $itemTransfer) {
                        return $itemTransfer->getUuid();
                    },
                    $itemsTransfers)
                )
                ->setShippingAddress($shipmentTransfer->getShippingAddress())
                ->setMethodName($shipmentTransfer->getMethod()->getName())
                ->setCarrierName($shipmentTransfer->getCarrier()->getName())
                ->setRequestedDeliveryDate($shipmentTransfer->getRequestedDeliveryDate() ?? null);

            $restOrderShipmentsAttributesTransfers[$shipmentTransfer->getIdSalesShipment()] = $restOrderShipmentsAttributesTransfer;
        }

        return $restOrderShipmentsAttributesTransfers;
    }
}
