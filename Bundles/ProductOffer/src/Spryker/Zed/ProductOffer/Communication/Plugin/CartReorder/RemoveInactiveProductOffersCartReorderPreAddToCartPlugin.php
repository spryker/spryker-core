<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Communication\Plugin\CartReorder;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderPreAddToCartPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOffer\Business\ProductOfferBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\ProductOffer\Communication\ProductOfferCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOffer\ProductOfferConfig getConfig()
 */
class RemoveInactiveProductOffersCartReorderPreAddToCartPlugin extends AbstractPlugin implements CartReorderPreAddToCartPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CartChangeTransfer.quote` to be set.
     * - Requires `CartChangeTransfer.quote.store` to be set.
     * - Filters out inactive and not approved product offer items from `CartChangeTransfer`.
     * - Adds a message for each item sku that it is not active.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function preAddToCart(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getBusinessFactory()
            ->createInactiveProductOfferItemsFilter()
            ->filterOutInactiveCartChangeProductOfferItems($cartChangeTransfer);
    }
}
