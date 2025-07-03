<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOffer\Business\ProductOfferBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\ProductOffer\ProductOfferConfig getConfig()
 * @method \Spryker\Zed\ProductOffer\Communication\ProductOfferCommunicationFactory getFactory()
 */
class OrderAmendmentProductOfferCartPreCheckPlugin extends AbstractPlugin implements CartPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CartChangeTransfer.quote` to be set.
     * - Check if cart items product offer belongs to product.
     * - Returns pre-check transfer with error messages in case of error.
     * - Skips product offers that are part of the original order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function check(CartChangeTransfer $cartChangeTransfer)
    {
        $itemProductOfferReferencesToSkipValidation = $this->getBusinessFactory()
            ->createQuoteOriginalSalesOrderItemExtractor()
            ->extractOriginalSalesOrderItemProductOfferReferences($cartChangeTransfer->getQuoteOrFail());

        return $this->getBusinessFactory()
            ->createItemProductOfferChecker()
            ->checkItemProductOffer($cartChangeTransfer, $itemProductOfferReferencesToSkipValidation);
    }
}
