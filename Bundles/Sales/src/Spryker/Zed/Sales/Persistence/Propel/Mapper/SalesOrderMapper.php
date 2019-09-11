<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaginationTransfer;

class SalesOrderMapper
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder[] $orderEntities
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param int $ordersCount
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function mapSalesOrderEntitiesToOrderListTransfer(array $orderEntities, OrderListTransfer $orderListTransfer, int $ordersCount): OrderListTransfer
    {
        foreach ($orderEntities as $orderEntity) {
            $orderListTransfer->addOrder(
                (new OrderTransfer())->fromArray($orderEntity->toArray(), true)
            );
        }

        return $orderListTransfer->setPagination((new PaginationTransfer())->setNbResults($ordersCount));
    }
}
