<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment\Items;

use \ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

class ItemsGrouper implements ItemsGrouperInterface
{
    protected const SHIPMENT_TRANSFER_KEY_PATTERN = '%s-%s-%s';
    protected const ADDRESS_TRANSFER_KEY_PATTERN = '%s %s %s %s %s %s %s %s %s %s';

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function groupByShipment(ArrayObject $itemTransfers): ArrayObject
    {
        $shipmentGroupTransfers = new ArrayObject();

        foreach ($itemTransfers as $itemTransfer) {
            $this->assertRequiredShipment($itemTransfer);

            $hash = $this->getItemHash($itemTransfer->getShipment());
            if (!isset($shipmentGroupTransfers[$hash])) {
                $shipmentGroupTransfers[$hash] = $this->createNewShipmentGroupTransfer($itemTransfer->getShipment());
            }

            $shipmentGroupTransfers[$hash]->addItem($itemTransfer);
        }

        return $shipmentGroupTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function assertRequiredShipment(ItemTransfer $itemTransfer): void
    {
        $itemTransfer->requireShipment();
        $itemTransfer->getShipment()->requireMethod();
        $itemTransfer->getShipment()->requireShippingAddress();
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return string
     */
    protected function getItemHash(ShipmentTransfer $shipmentTransfer): string
    {
        return sprintf(
            static::SHIPMENT_TRANSFER_KEY_PATTERN,
            $shipmentTransfer->getMethod()->getIdShipmentMethod(),
            $this->getAddressTransferKey($shipmentTransfer->getShippingAddress()),
            $shipmentTransfer->getRequestedDeliveryDate()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return string
     */
    protected function getAddressTransferKey(AddressTransfer $addressTransfer): string
    {
        return sprintf(
            static::ADDRESS_TRANSFER_KEY_PATTERN,
            $addressTransfer->getFkCustomer(),
            $addressTransfer->getFirstName(),
            $addressTransfer->getLastName(),
            $addressTransfer->getAddress1(),
            $addressTransfer->getAddress2(),
            $addressTransfer->getAddress3(),
            $addressTransfer->getZipCode(),
            $addressTransfer->getCity(),
            $addressTransfer->getFkCountry(),
            $addressTransfer->getPhone()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return string
     */
    protected function createNewShipmentGroupTransfer(ShipmentTransfer $shipmentTransfer): ShipmentGroupTransfer
    {
        return (new ShipmentGroupTransfer())->setShipment($shipmentTransfer);
    }
}