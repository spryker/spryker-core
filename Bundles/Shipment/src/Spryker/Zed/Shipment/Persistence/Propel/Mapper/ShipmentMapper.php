<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesShipment;

class ShipmentMapper implements ShipmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param int $idSalesOrder
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipmentEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipment
     */
    public function mapShipmentTransferToSalesOrderAddressEntity(ShipmentTransfer $shipmentTransfer, int $idSalesOrder, SpySalesShipment $salesShipmentEntity): SpySalesShipment
    {
        $salesShipmentEntity->fromArray($shipmentTransfer->getMethod()->toArray());
        $salesShipmentEntity->setFkSalesOrder($idSalesOrder);
        $salesShipmentEntity->setFkSalesOrderAddress($shipmentTransfer->getShippingAddress()->getIdSalesOrderAddress());

        return $salesShipmentEntity;
    }
}
