<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Communication\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

class SalesOrderMapper implements SalesOrderMapperInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function mapSalesOrderToOrderTransfer(
        SpySalesOrder $salesOrderEntity,
        OrderTransfer $orderTransfer
    ): OrderTransfer {
        return $orderTransfer->fromArray($salesOrderEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function mapSalesOrderItemEntityToItemTransfer(
        SpySalesOrderItem $salesOrderItemEntity,
        ItemTransfer $itemTransfer
    ): ItemTransfer {
        return $itemTransfer->fromArray($salesOrderItemEntity->toArray(), true);
    }
}
