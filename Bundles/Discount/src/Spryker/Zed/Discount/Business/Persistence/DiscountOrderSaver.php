<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Persistence;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;
use Orm\Zed\Sales\Persistence\SpySalesDiscountCode;
use Spryker\Zed\Discount\Business\Voucher\VoucherCodeInterface;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Spryker\Zed\Discount\Business\Checkout\DiscountOrderSaver as CheckoutDiscountOrderSaver;

/**
 * @deprecated Use \Spryker\Zed\Discount\Business\Checkout\DiscountOrderSaver instead
 * Will be removed with the next major release
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
