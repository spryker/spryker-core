<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Client\Sales\Zed;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface SalesStubInterface
{

    /**
     * @param OrderListTransfer $orderListTransfer
     *
     * @return OrderListTransfer
     */
    public function getOrders(OrderListTransfer $orderListTransfer);

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return OrderTransfer
     */
    public function getOrderDetails(OrderTransfer $orderTransfer);

}
