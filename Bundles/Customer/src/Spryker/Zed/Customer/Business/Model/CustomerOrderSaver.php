<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Model;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Customer\Business\Checkout\CustomerOrderSaver as CheckoutCustomerOrderSaver;

/**
 * @deprecated Use {@link \Spryker\Zed\Customer\Business\Checkout\CustomerOrderSaver} instead
 */
class CustomerOrderSaver extends CheckoutCustomerOrderSaver implements CustomerOrderSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this->saveOrderCustomer($quoteTransfer, $checkoutResponseTransfer->getSaveOrder());
    }
}
