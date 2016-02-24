<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface SalesOrderSaverInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $order
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveOrder(OrderTransfer $order, CheckoutResponseTransfer $checkoutResponse);

}
