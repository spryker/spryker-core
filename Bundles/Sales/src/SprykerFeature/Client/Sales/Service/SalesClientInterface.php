<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Sales\Service;

use Generated\Shared\Sales\OrderListInterface;

interface SalesClientInterface
{
    /**
     * @param OrderListInterface $orderListTransfer
     * 
     * @return OrderListInterface
     */
    public function getOrders(OrderListInterface $orderListTransfer);

}
