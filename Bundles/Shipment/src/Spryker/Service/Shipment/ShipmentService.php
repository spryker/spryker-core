<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment;

use Generated\Shared\Transfer\ShipmentGroupsTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Service\Kernel\AbstractService;
use ArrayObject;

/**
 * @method \Spryker\Service\Shipment\ShipmentServiceFactory getFactory()
 */
class ShipmentService extends AbstractService implements ShipmentServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfersCollection
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupsTransfer
     */
    public function groupItemsByShipment(ArrayObject $itemTransfersCollection): ShipmentGroupsTransfer
    {
        $shipmentGroupsArray = new ArrayObject();

        foreach ($itemTransfersCollection as $itemTransfer) {
            $itemTransfer->requireShipment();

            $hash = $this->getItemHash($itemTransfer->getShipment());
            if (!isset($shipmentGroupsArray[$hash])) {
                $shipmentGroupsArray[$hash] = (new ShipmentGroupTransfer())
                    ->setShipment($itemTransfer->getShipment())
                    ->setHash($hash);
            }

            $shipmentGroupsArray[$hash]->addItem($itemTransfer);
        }

        $shipmentGroupsTransfers = new ShipmentGroupsTransfer();
        $shipmentGroupsTransfers->setGroups($shipmentGroupsArray);

        return $shipmentGroupsTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return string
     */
    protected function getItemHash(ShipmentTransfer $shipmentTransfer): string
    {
        $shippingMethod = '';

        if ($shipmentTransfer->getMethod() !== null) {
            $shippingMethod = (string)$shipmentTransfer->getMethod()->getIdShipmentMethod();
        }

        return md5(implode([
            $shippingMethod,
            $shipmentTransfer->getShippingAddress()->serialize(),
            $shipmentTransfer->getRequestedDeliveryDate(),
        ]));
    }
}
