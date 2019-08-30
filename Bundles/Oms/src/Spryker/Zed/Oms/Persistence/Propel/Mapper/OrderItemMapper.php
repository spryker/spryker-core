<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

class OrderItemMapper
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function mapOrderItemEntityToItemTransfer(SpySalesOrderItem $orderItemEntity, ItemTransfer $itemTransfer): ItemTransfer
    {
        $itemTransfer->fromArray($orderItemEntity->toArray(), true);

        /**
         * @todo Move this part outside from the Oms module.
         */
        $shipmentTransfer = (new ShipmentTransfer())
            ->setIdSalesShipment($orderItemEntity->getFkSalesShipment());

        return $itemTransfer->setShipment($shipmentTransfer);
    }
}
