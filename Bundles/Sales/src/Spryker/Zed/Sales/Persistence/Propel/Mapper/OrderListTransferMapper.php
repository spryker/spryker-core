<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\PaginationTransfer;

class OrderListTransferMapper implements OrderListTransferMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param array $orderList
     * @param int $nbResults
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function mapOrderListTransfer(OrderListTransfer $orderListTransfer, array $orderList, int $nbResults): OrderListTransfer
    {
        return $orderListTransfer
            ->setOrders(new ArrayObject($orderList))
            ->setPagination((new PaginationTransfer())->setNbResults($nbResults));
    }
}
