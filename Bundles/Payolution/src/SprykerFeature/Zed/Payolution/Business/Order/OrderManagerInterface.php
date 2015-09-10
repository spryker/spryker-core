<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Order;

use Generated\Shared\Payolution\OrderInterface;

interface OrderManagerInterface
{
    public function saveOrderPayment(OrderInterface $orderTransfer);
}
