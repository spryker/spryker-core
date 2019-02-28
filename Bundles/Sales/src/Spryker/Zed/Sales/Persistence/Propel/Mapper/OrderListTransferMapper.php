<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\PaginationTransfer;

class OrderListTransferMapper
{
    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder[] $orders
     * @param int $ordersCount
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function mapPaginatedOrderListTransfer(OrderListTransfer $orderListTransfer, array $orders, int $ordersCount): OrderListTransfer
    {
        return $orderListTransfer
            ->setOrders(new ArrayObject($orders))
            ->setPagination((new PaginationTransfer())->setNbResults($ordersCount));
    }
}
