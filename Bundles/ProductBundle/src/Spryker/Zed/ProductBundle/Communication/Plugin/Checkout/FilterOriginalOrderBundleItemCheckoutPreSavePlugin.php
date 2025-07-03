<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreSavePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\ProductBundle\Communication\ProductBundleCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductBundle\ProductBundleConfig getConfig()
 */
class FilterOriginalOrderBundleItemCheckoutPreSavePlugin extends AbstractPlugin implements CheckoutPreSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `QuoteTransfer.bundleItems.sku` to be set.
     * - Requires `QuoteTransfer.originalSalesOrderItems.sku` to be set.
     * - Filters `QuoteTransfer.originalSalesOrderItems` by removing items that are provided in `QuoteTransfer.bundleItems`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preSave(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getBusinessFactory()
            ->createOriginalOrderBundleItemFilter()
            ->filterOriginalSalesOrderItems($quoteTransfer);
    }
}
