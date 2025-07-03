<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOffer\Business\ProductOfferBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\ProductOffer\Communication\ProductOfferCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOffer\ProductOfferConfig getConfig()
 */
class OrderAmendmentProductOfferCheckoutPreConditionPlugin extends AbstractPlugin implements CheckoutPreConditionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns `false` response if at least one quote item transfer has items with inactive or not approved ProductOffer.
     * - Sets error messages to checkout response, in case if items contain inactive or not approved ProductOffer items.
     * - Skips product offers that are part of the original order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCondition(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): bool {
        $itemProductOfferReferencesToSkipValidation = $this->getBusinessFactory()
            ->createQuoteOriginalSalesOrderItemExtractor()
            ->extractOriginalSalesOrderItemProductOfferReferences($quoteTransfer);

        return $this->getBusinessFactory()
            ->createProductOfferCheckoutValidator()
            ->isQuoteReadyForCheckout(
                $quoteTransfer,
                $checkoutResponseTransfer,
                $itemProductOfferReferencesToSkipValidation,
            );
    }
}
