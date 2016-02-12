<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Business\Order;

use Generated\Shared\Transfer\OrderTransfer;

interface OrderManagerInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function saveOrder(OrderTransfer $orderTransfer);

}
