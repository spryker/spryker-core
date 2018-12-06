<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Service\Kernel\AbstractService;
use iterable;

/**
 * @method \Spryker\Service\Shipment\ShipmentServiceFactory getFactory()
 */
class ShipmentService extends AbstractService implements ShipmentServiceInterface
{
    /**
     * Specification:
     * - Iterates all items grouping them by shipment.
     *
     * @api
     *
     * @param \iterable $items
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function groupItemsByShipment(iterable $items): array
    {
        $shipmentGroupTransfers = [];

        foreach ($items as $item){
            $hash = $this->getItemHash($item);
            if (isset($shipmentGroupTransfers[$hash])) {
                $shipmentGroupTransfers[$hash]->addItem($item);
                continue;
            }

            $shipmentGroupTransfers[$hash] = (new ShipmentGroupTransfer())
                ->setShipment($item->getShipment())
                ->addItem($item);
        }

        return $shipmentGroupTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     *
     * @return string
     */
    protected function getItemHash(ItemTransfer $item): string
    {
        return $item->getShipment()->getMethod()->getIdShipmentMethod()
            . md5(
                $item->getShipment()->getShippingAddress()->serialize()
                . $item->getShipment()->getRequestedDeliveryDate()
            );
    }
}
