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
     * @return int
     */
    public function createSalesShipment(
        ShipmentTransfer $shipmentTransfer,
        OrderTransfer $orderTransfer,
        ?ExpenseTransfer $expenseTransfer = null
    ): int {
        
        $salesShipmentEntity = $this->getFactory()
            ->createSalesShipmentQuery()
            ->findOneByIdSalesShipment($shipmentTransfer->getIdSalesShipment());

        if ($salesShipmentEntity === null) {
            $salesShipmentEntity = new SpySalesShipment();
        }

        $salesShipmentEntity = $this->getFactory()
            ->createShipmentMapper()
            ->mapShipmentTransferToSalesOrderAddressEntity($shipmentTransfer, $orderTransfer->getIdSalesOrder(), $salesShipmentEntity);

        if ($expenseTransfer !== null && $expenseTransfer->getIdSalesExpense() !== null) {
            $salesShipmentEntity->setFkSalesExpense($expenseTransfer->getIdSalesExpense());
        }

        $salesShipmentEntity->save();

        return $salesShipmentEntity->getIdSalesShipment();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $idSalesShipment
     *
     * @return void
     */
    public function updateSalesOrderItemFkShipment(ItemTransfer $itemTransfer, int $idSalesShipment): void
    {
        $orderItemEntity = $this->getFactory()
            ->createSalesOrderItemQuery()
            ->filterByIdSalesOrderItem($itemTransfer->getIdSalesOrderItem())
            ->findOneOrCreate();

        $orderItemEntity->setFkSalesShipment($idSalesShipment);

        $orderItemEntity->save();
    }
}
