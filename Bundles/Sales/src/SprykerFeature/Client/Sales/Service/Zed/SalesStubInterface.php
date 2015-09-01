<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Client\Sales\Service\Zed;

use Generated\Shared\Sales\OrderListInterface;

interface SalesStubInterface
{
    /**
     * @param OrderListInterface $orderListTransfer
     * 
     * @return OrderListInterface
     */
    public function getOrders(OrderListInterface $orderListTransfer);

}
