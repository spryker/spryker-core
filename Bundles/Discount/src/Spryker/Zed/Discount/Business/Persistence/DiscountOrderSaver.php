<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Persistence;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\Checkout\DiscountOrderSaver as CheckoutDiscountOrderSaver;

/**
 * @deprecated Use \Spryker\Zed\Discount\Business\Checkout\DiscountOrderSaver instead
 */
class DiscountOrderSaver extends CheckoutDiscountOrderSaver implements DiscountOrderSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveDiscounts(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $checkoutResponseTransfer->requireSaveOrder();

        $this->saveOrderDiscounts($quoteTransfer, $checkoutResponseTransfer->getSaveOrder());
    }
}
