<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Client\Sales\Service\Zed;

use Generated\Shared\Sales\OrderListInterface;
use Generated\Shared\Transfer\OrderTransfer;

interface SalesStubInterface
{

    /**
     * @param OrderListInterface $orderListTransfer
     * 
     * @return OrderListInterface
     */
    public function getOrders(OrderListInterface $orderListTransfer);

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return OrderTransfer
     */
    public function getOrderDetails(OrderTransfer $orderTransfer);

}
