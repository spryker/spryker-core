<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Customer;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\PaginationTransfer;

class FilteredCustomerOrderListReader extends CustomerOrderReader
{
    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOrders(OrderListTransfer $orderListTransfer, $idCustomer): OrderListTransfer
    {
        $orderListTransfer->requireIdCustomer();

        $ordersQuery = $this->queryContainer->queryCustomerOrders($idCustomer);
        $numberOfOrders = $ordersQuery->count();
        if (!$numberOfOrders) {
            return $orderListTransfer;
        }

        $filterTransfer = $orderListTransfer->getFilter();
        if ($filterTransfer) {
            $ordersQuery
                ->setLimit($filterTransfer->getLimit())
                ->setOffset($filterTransfer->getOffset());
        }

        $orders = $this->hydrateOrderListCollectionTransferFromEntityCollection($ordersQuery->find());
        $orderListTransfer->setOrders($orders);
        $orderListTransfer->setPagination((new PaginationTransfer())->setNbResults($numberOfOrders));

        return $orderListTransfer;
    }
}
