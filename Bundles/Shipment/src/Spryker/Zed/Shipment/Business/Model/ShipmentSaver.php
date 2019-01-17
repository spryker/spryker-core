<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface;

class ShipmentSaver implements ShipmentSaverInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface $entityManager
     */
    public function __construct(
        ShipmentEntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return void
     */
    public function saveShipment(ShipmentTransfer $shipmentTransfer)
    {
        $this->entityManager->createSalesShipment($shipmentTransfer, $shipmentTransfer->getOrder()->getIdSalesOrder());
        $this->updateShipmentItems($shipmentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return void
     */
    protected function updateShipmentItems(ShipmentTransfer $shipmentTransfer)
    {
        foreach ($shipmentTransfer->getShipmentItems() as $itemTransfer) {
            $this->entityManager->updateSalesOrderItemFkShipment($itemTransfer, $shipmentTransfer->getIdSalesShipment());
        }
    }
}
