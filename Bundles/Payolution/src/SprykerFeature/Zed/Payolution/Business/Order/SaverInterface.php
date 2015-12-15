<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Business\Order;

use Generated\Shared\Transfer\OrderTransfer;

interface SaverInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     */
    public function saveOrderPayment(OrderTransfer $orderTransfer);

}
