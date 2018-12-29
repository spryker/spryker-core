<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment;

use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Service\Kernel\AbstractService;
use \ArrayObject;

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
     * @param \Traversable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function groupItemsByShipment(ArrayObject $itemTransfers): ArrayObject
    {
        $shipmentGroupTransfers = new ArrayObject();

        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfer->requireShipment();

            $hash = $this->getItemHash($itemTransfer->getShipment());
            if (isset($shipmentGroupTransfers[$hash])) {
                $shipmentGroupTransfers[$hash]->addItem($itemTransfer);
                continue;
            }

            $shipmentGroupTransfers[$hash] = (new ShipmentGroupTransfer())
                ->setShipment($itemTransfer->getShipment())
                ->addItem($itemTransfer);
        }

        return $shipmentGroupTransfers;
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
