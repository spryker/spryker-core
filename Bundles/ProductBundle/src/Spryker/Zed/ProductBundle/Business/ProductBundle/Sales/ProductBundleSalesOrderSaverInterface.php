<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Sales;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @deprecated Use {@link \Spryker\Zed\ProductBundle\Business\ProductBundle\Checkout\ProductBundleSalesOrderSaverInterface} instead
 */
interface ProductBundleSalesOrderSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveSaleOrderBundleItems(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse);
}
