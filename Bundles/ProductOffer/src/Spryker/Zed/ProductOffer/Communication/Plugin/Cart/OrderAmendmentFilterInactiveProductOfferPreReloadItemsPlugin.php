<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Communication\Plugin\Cart;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\PreReloadItemsPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOffer\Business\ProductOfferBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\ProductOffer\ProductOfferConfig getConfig()
 * @method \Spryker\Zed\ProductOffer\Communication\ProductOfferCommunicationFactory getFactory()
 */
class OrderAmendmentFilterInactiveProductOfferPreReloadItemsPlugin extends AbstractPlugin implements PreReloadItemsPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks and removes inactive product offers from the cart.
     * - Skips inactive product offers that are part of the original order.
     * - Adds info messages for the removed product offers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preReloadItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $itemProductOfferReferencesToSkipFiltering = $this->getBusinessFactory()
            ->createQuoteOriginalSalesOrderItemExtractor()
            ->extractOriginalSalesOrderItemProductOfferReferences($quoteTransfer);

        return $this->getBusinessFactory()
            ->createInactiveProductOfferItemsFilter()
            ->filterInactiveProductOfferItems($quoteTransfer, $itemProductOfferReferencesToSkipFiltering);
    }
}
