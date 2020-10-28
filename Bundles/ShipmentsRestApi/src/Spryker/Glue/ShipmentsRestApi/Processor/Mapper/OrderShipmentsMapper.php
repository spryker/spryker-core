<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\RestOrderShipmentsAttributesTransfer;

class OrderShipmentsMapper implements OrderShipmentsMapperInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param \Generated\Shared\Transfer\RestOrderShipmentsAttributesTransfer[] $restOrderShipmentsAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestOrderShipmentsAttributesTransfer[]
     */
    public function mapItemTransfersToRestOrderShipmentsAttributesTransfer(
        ArrayObject $itemTransfers,
        array $restOrderShipmentsAttributesTransfers = []
    ): array {
        foreach ($itemTransfers as $itemTransfer) {
            $shipmentTransfer = $itemTransfer->getShipment();
            $restOrderShipmentsAttributesTransfer = (new RestOrderShipmentsAttributesTransfer())
                ->fromArray($itemTransfer->toArray(), true)
                ->setItemUuids($itemTransfer->getUuid())
                ->setShippingAddress($shipmentTransfer->getShippingAddress())
                ->setMethodName($shipmentTransfer->getMethod()->getName())
                ->setCarrierName($shipmentTransfer->getCarrier()->getName())
                ->setRequestedDeliveryDate(
                    $shipmentTransfer->getRequestedDeliveryDate() ?
                        $shipmentTransfer->getRequestedDeliveryDate() : null
                );
            //TODO fix the shipmentId of ItemTransfer to be set to the $restOrderShipmentsAttributesTransfers as a key.
            $restOrderShipmentsAttributesTransfers[$shipmentTransfer->getIdSalesShipment()] = $restOrderShipmentsAttributesTransfer;
        }

        return $restOrderShipmentsAttributesTransfers;
    }
}
