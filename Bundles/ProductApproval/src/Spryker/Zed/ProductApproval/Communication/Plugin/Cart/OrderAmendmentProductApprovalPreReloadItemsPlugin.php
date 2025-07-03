<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Communication\Plugin\Cart;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\PreReloadItemsPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductApproval\Business\ProductApprovalFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductApproval\ProductApprovalConfig getConfig()
 * @method \Spryker\Zed\ProductApproval\Business\ProductApprovalBusinessFactory getBusinessFactory()
 */
class OrderAmendmentProductApprovalPreReloadItemsPlugin extends AbstractPlugin implements PreReloadItemsPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `QuoteTransfer.originalSalesOrderItems.sku` to be set.
     * - Checks and removes not approved product items from Quote transfer.
     * - Skips items that are part of the original order.
     * - Adds info messages for the removed products.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preReloadItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $itemSkusToSkipFiltering = $this->getBusinessFactory()
            ->createQuoteOriginalSalesOrderItemExtractor()
            ->extractOriginalSalesOrderItemSkus($quoteTransfer);

        return $this->getBusinessFactory()
            ->createQuoteItemsFilter()
            ->filterCartItems($quoteTransfer, $itemSkusToSkipFiltering);
    }
}
