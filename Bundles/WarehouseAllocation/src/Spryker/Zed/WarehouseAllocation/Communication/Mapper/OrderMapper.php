<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseAllocation\Communication\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

class OrderMapper implements OrderMapperInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param list<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItemEntities
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function mapOrderEntityAndOrderItemEntitiesToOrderTransfer(
        SpySalesOrder $orderEntity,
        array $orderItemEntities,
        OrderTransfer $orderTransfer
    ): OrderTransfer {
        $orderTransfer->fromArray($orderEntity->toArray(), true);
        foreach ($orderItemEntities as $orderItemEntity) {
            $orderTransfer->addItem(
                $this->mapOrderItemEntityToItemTransfer($orderItemEntity, new ItemTransfer()),
            );
        }

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function mapOrderItemEntityToItemTransfer(SpySalesOrderItem $orderItemEntity, ItemTransfer $itemTransfer): ItemTransfer
    {
        return $itemTransfer->fromArray($orderItemEntity->toArray(), true);
    }
}
