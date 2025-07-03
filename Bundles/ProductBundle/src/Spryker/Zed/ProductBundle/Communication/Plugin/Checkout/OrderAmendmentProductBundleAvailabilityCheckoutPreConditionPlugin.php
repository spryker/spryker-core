<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\ProductBundle\Communication\ProductBundleCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductBundle\ProductBundleConfig getConfig()
 */
class OrderAmendmentProductBundleAvailabilityCheckoutPreConditionPlugin extends AbstractPlugin implements CheckoutPreConditionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `OriginalSalesOrderItemTransfer.sku` to be set for each item in `CartChangeTransfer.quote.originalSalesOrderItems`.
     * - Checks if the product bundle items in the `QuoteTransfer` are available and active.
     * - Skips `isActive` validation for items with SKUs from `QuoteTransfer.originalSalesOrderItems`.
     * - Returns `true` if all product bundle items are available.
     * - Otherwise, returns `false`, sets `CheckoutResponseTransfer.isSuccess` to `false` and adds error messages to the `CheckoutResponseTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        $originalSalesOrderItemSkus = $this->getBusinessFactory()
            ->createQuoteOriginalSalesOrderItemExtractor()
            ->extractOriginalSalesOrderItemSkus($quoteTransfer);

        return $this->getBusinessFactory()
            ->createProductBundleCheckoutPreCheck()
            ->checkCheckoutAvailability($quoteTransfer, $checkoutResponseTransfer, $originalSalesOrderItemSkus);
    }
}
