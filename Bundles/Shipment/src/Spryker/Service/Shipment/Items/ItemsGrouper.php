<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment\Items;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

class ItemsGrouper implements ItemsGrouperInterface
{
    protected const SHIPMENT_TRANSFER_KEY_PATTERN = '%s-%s-%s';

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

            $key = $this->getItemShipmentKey($itemTransfer->getShipment());
            if (!isset($shipmentGroupTransfers[$key])) {
                $shipmentGroupTransfers[$key] = $this
                    ->createNewShipmentGroupTransfer()
                    ->setShipment($itemTransfer->getShipment())
                    ->setHash($key);
            }

            $shipmentGroupTransfers[$key]->addItem($itemTransfer);
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
    protected function getItemShipmentKey(ShipmentTransfer $shipmentTransfer): string
    {
        return sprintf(
            static::SHIPMENT_TRANSFER_KEY_PATTERN,
            $shipmentTransfer->getMethod()->getIdShipmentMethod(),
            $shipmentTransfer->getShippingAddress()->serialize(),
            $shipmentTransfer->getRequestedDeliveryDate()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    protected function createNewShipmentGroupTransfer(): ShipmentGroupTransfer
    {
        return new ShipmentGroupTransfer();
    }
}
