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
            ->setShippingAddress($this->createRestAddressTransfer($shipmentGroupTransfer))
            ->setSelectedShipmentMethod($this->createRestShipmentMethodTransfer($shipmentGroupTransfer))
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

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \Generated\Shared\Transfer\RestAddressTransfer
     */
    protected function createRestAddressTransfer(ShipmentGroupTransfer $shipmentGroupTransfer): RestAddressTransfer
    {
        $addressTransfer = $shipmentGroupTransfer->getShipment()->getShippingAddress();
        if (!$addressTransfer) {
            return new RestAddressTransfer();
        }

        return (new RestAddressTransfer())
            ->fromArray($addressTransfer->toArray(), true)
            ->setId($addressTransfer->getUuid());
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \Generated\Shared\Transfer\RestShipmentMethodTransfer
     */
    protected function createRestShipmentMethodTransfer(
        ShipmentGroupTransfer $shipmentGroupTransfer
    ): RestShipmentMethodTransfer {
        $shipmentMethodTransfer = $shipmentGroupTransfer->getShipment()->getMethod();
        if (!$shipmentMethodTransfer) {
            return new RestShipmentMethodTransfer();
        }

        return (new RestShipmentMethodTransfer())
            ->fromArray($shipmentMethodTransfer->toArray(), true)
            ->setPrice($shipmentMethodTransfer->getStoreCurrencyPrice())
            ->setId($shipmentMethodTransfer->getIdShipmentMethod());
    }
}
