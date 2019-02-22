<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Customer;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\PaginationTransfer;

class CustomerOrderChunkReader extends CustomerOrderReader implements CustomerOrderChunkReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getCustomerChunkOrderList(OrderListTransfer $orderListTransfer, int $idCustomer): OrderListTransfer
    {
        $ordersQuery = $this->queryContainer->queryCustomerOrders(
            $idCustomer,
            $orderListTransfer->getFilter()
        );

        $orders = $this->hydrateOrderListCollectionTransferFromEntityCollection($ordersQuery->find());

        $orderListTransfer->setOrders($orders);
        $orderListTransfer->setPagination((new PaginationTransfer())->setNbResults($ordersQuery->clear()->count()));

        return $orderListTransfer;
    }
}
