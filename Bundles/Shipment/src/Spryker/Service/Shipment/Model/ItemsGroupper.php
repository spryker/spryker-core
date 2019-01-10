<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment\Model;

use \ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

class ItemsGroupper implements ItemsGroupperInterface
{
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
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return string
     */
    protected function getItemHash(ShipmentTransfer $shipmentTransfer): string
    {
        return $shipmentTransfer->getMethod()->getIdShipmentMethod()
            . $shipmentTransfer->getShippingAddress()->serialize()
            . $shipmentTransfer->getRequestedDeliveryDate();
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