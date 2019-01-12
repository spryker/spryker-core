<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentPersistenceFactory getFactory()
 */
class ShipmentEntityManager extends AbstractEntityManager implements ShipmentEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return int
     */
    public function createSalesShipment(ShipmentTransfer $shipmentTransfer, int $idSalesOrder): int
    {
        $salesShipmentEntity = $this->getFactory()
            ->createShipmentMapper()
            ->mapShipmentTransferToSalesOrderAddressEntity($shipmentTransfer, $idSalesOrder);

        $salesShipmentEntity->save();

        return $salesShipmentEntity->getIdSalesShipment();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function updateSalesOrderItemFkShipment(ItemTransfer $itemTransfer, int $idSalesOrder): void
    {
        $orderItemEntity = $this->getFactory()
            ->createShipmentMapper()
            ->mapItemTransferToSalesOrderItemEntity($itemTransfer, $idSalesOrder);

        $orderItemEntity->save();
    }
}
