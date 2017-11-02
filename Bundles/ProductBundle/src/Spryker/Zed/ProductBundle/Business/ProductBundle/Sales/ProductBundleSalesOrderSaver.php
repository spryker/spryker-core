<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Sales;

use Exception;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\ProductBundle\Persistence\SpySalesOrderItemBundle;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToSalesQueryContainerInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;
use Throwable;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Checkout\ProductBundleOrderSaver;

/**
 * @deprecated Use Spryker\Zed\ProductBundle\Business\ProductBundle\Checkout\ProductBundleSalesOrderSaver instead
 * Will be removed in the next major release
 */
class ProductBundleSalesOrderSaver extends ProductBundleOrderSaver implements ProductBundleSalesOrderSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @throws \Exception
     * @throws \Throwable
     *
     * @return void
     */
    public function saveSaleOrderBundleItems(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        parent::saveOrderBundleItems($quoteTransfer, $checkoutResponse->getSaveOrder());
    }
}
