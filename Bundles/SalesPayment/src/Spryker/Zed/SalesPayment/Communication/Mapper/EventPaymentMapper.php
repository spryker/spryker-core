<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Communication\Mapper;

use Generated\Shared\Transfer\EventPaymentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

class EventPaymentMapper implements EventPaymentMapperInterface
{
    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderSales
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\EventPaymentTransfer
     */
    public function mapOrderEntityAndOrderItemEntitiesToEventPaymentTransfer(
        array $orderItems,
        SpySalesOrder $orderSales,
        EventPaymentTransfer $eventPaymentTransfer
    ): EventPaymentTransfer {
        $orderItemIds = [];

        foreach ($orderItems as $orderItem) {
            $orderItemIds[] = $orderItem->getIdSalesOrderItem();
        }

        return $eventPaymentTransfer
            ->setIdSalesOrder($orderSales->getIdSalesOrder())
            ->setOrderItemIds($orderItemIds);
    }
}
