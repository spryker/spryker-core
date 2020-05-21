<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Sales;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Checkout\ProductBundleOrderSaver;

/**
 * @deprecated Use {@link \Spryker\Zed\ProductBundle\Business\ProductBundle\Checkout\ProductBundleSalesOrderSaver} instead
 */
class ProductBundleSalesOrderSaver extends ProductBundleOrderSaver implements ProductBundleSalesOrderSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveSaleOrderBundleItems(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        parent::saveOrderBundleItems($quoteTransfer, $checkoutResponse->getSaveOrder());
    }
}
