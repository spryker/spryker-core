<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentPersistenceFactory getFactory()
 */
class ShipmentEntityManager extends AbstractEntityManager implements ShipmentEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer|null $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function createOrderShipment(
        ShipmentTransfer $shipmentTransfer,
        OrderTransfer $orderTransfer,
        ?ExpenseTransfer $expenseTransfer = null
    ): ShipmentTransfer {
        
        $salesShipmentEntity = $this->getFactory()
            ->createSalesShipmentQuery()
            ->findOneByIdSalesShipment($shipmentTransfer->getIdSalesShipment());

        if ($salesShipmentEntity === null) {
            $salesShipmentEntity = new SpySalesShipment();
        }

        $shipmentEntityMapper = $this->getFactory()->createShipmentMapper();
        $salesShipmentEntity = $shipmentEntityMapper->mapShipmentTransferToShipmentEntity($salesShipmentEntity, $shipmentTransfer);
        $salesShipmentEntity = $shipmentEntityMapper->mapOrderTransferToShipmentEntity($salesShipmentEntity, $orderTransfer);
        $salesShipmentEntity = $shipmentEntityMapper->mapExpenseTransferToShipmentEntity($salesShipmentEntity, $expenseTransfer);

        $salesShipmentEntity->save();

        return $shipmentEntityMapper->mapShipmentEntityToShipmentTransfer($shipmentTransfer, $salesShipmentEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function updateOrderItemFkShipment(ItemTransfer $itemTransfer, ShipmentTransfer $shipmentTransfer): ItemTransfer
    {
        $orderItemEntity = $this->getFactory()
            ->createSalesOrderItemQuery()
            ->filterByIdSalesOrderItem($itemTransfer->getIdSalesOrderItem())
            ->findOneOrCreate();

        $orderItemEntity->setFkSalesShipment($shipmentTransfer->getIdSalesShipment());

        $orderItemEntity->save();

        return $itemTransfer;
    }
}
