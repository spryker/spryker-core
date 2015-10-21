<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Order;

use Generated\Shared\Payone\OrderInterface as PayoneOrderInterface;

interface OrderManagerInterface
{

    /**
     * @param PayoneOrderInterface $orderTransfer
     *
     * @return void
     */
    public function saveOrder(PayoneOrderInterface $orderTransfer);

}
