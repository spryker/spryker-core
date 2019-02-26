<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesShipment;

class ShipmentMapper implements ShipmentMapperInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipmentEntity
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipment
     */
    public function mapShipmentTransferToShipmentEntity(SpySalesShipment $salesShipmentEntity, ShipmentTransfer $shipmentTransfer): SpySalesShipment
    {
        $salesShipmentEntity->fromArray($shipmentTransfer->getMethod()->modifiedToArray());
        $salesShipmentEntity->setFkSalesOrderAddress($shipmentTransfer->getShippingAddress()->getIdSalesOrderAddress());

        return $salesShipmentEntity;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipmentEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipment
     */
    public function mapOrderTransferToShipmentEntity(SpySalesShipment $salesShipmentEntity, OrderTransfer $orderTransfer): SpySalesShipment
    {
        $salesShipmentEntity->setFkSalesOrder($orderTransfer->getIdSalesOrder());

        return $salesShipmentEntity;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipmentEntity
     * @param \Generated\Shared\Transfer\ExpenseTransfer|null $expenseTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipment
     */
    public function mapExpenseTransferToShipmentEntity(SpySalesShipment $salesShipmentEntity, ?ExpenseTransfer $expenseTransfer = null): SpySalesShipment
    {
        if ($expenseTransfer !== null && $expenseTransfer->getIdSalesExpense() !== null) {
            $salesShipmentEntity->setFkSalesExpense($expenseTransfer->getIdSalesExpense());
        }

        return $salesShipmentEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipmentEntity
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function mapShipmentEntityToShipmentTransfer(ShipmentTransfer $shipmentTransfer, SpySalesShipment $salesShipmentEntity): ShipmentTransfer
    {
        $shipmentTransfer->fromArray($salesShipmentEntity->toArray());

        return $salesShipmentEntity;
    }
}
