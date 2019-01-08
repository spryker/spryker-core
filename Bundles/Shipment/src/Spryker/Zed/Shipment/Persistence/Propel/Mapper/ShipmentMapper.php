<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesShipment;

class ShipmentMapper implements ShipmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipment
     */
    public function mapShipmentTransferToSalesOrderAddressEntity(ShipmentTransfer $shipmentTransfer, int $idSalesOrder): SpySalesShipment
    {
        $salesShipmentEntity = new SpySalesShipment();

        $salesShipmentEntity->fromArray($shipmentTransfer->getMethod()->toArray());
        $salesShipmentEntity->setFkSalesOrder($idSalesOrder);
        $salesShipmentEntity->setFkSalesExpense($shipmentTransfer->getExpense()->getIdSalesExpense());
        $salesShipmentEntity->setFkSalesOrderAddress($shipmentTransfer->getShippingAddress()->getIdSalesOrderAddress());

        return $salesShipmentEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $idSalesShipment
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    public function mapItemTransferToSalesOrderItemEntity(ItemTransfer $itemTransfer, int $idSalesShipment): SpySalesOrderItem
    {
        $orderItemEntity = new SpySalesOrderItem();

        $orderItemEntity->fromArray($itemTransfer->toArray());
        $orderItemEntity->setFkSalesShipment($idSalesShipment);

        return $orderItemEntity;
    }
}
