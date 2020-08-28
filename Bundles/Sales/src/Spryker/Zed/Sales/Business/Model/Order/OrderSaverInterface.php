<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Order;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @deprecated Use {@link \Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaverInterface} instead.
 */
interface OrderSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @throws \Exception
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer);
}
