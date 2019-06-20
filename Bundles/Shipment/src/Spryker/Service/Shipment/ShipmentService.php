<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Service\Kernel\AbstractService;

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
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function groupItemsByShipment(iterable $itemTransfers): ArrayObject
    {
        return $this->getFactory()->createItemsGrouper()->groupByShipment($itemTransfers);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return string
     */
    public function getShipmentHashKey(ShipmentTransfer $shipmentTransfer): string
    {
        return $this->getFactory()->createShipmentHashGenerator()->getShipmentHashKey($shipmentTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param string $shipmentHashKey
     *
     * @return bool
     */
    public function isShipmentEqualToShipmentHash(ShipmentTransfer $shipmentTransfer, string $shipmentHashKey): bool
    {
        return $this->getFactory()
            ->createShipmentHashGenerator()
            ->isShipmentEqualToShipmentHash($shipmentTransfer, $shipmentHashKey);
    }
}
