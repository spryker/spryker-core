<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Communication\Plugin\Cart;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Cart\Dependency\PreReloadItemsPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductOffer\Communication\ProductOfferCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOffer\ProductOfferConfig getConfig()
 */
class RemoveInactiveOfferItemsPreReloadPlugin extends AbstractPlugin implements PreReloadItemsPluginInterface
{
    /**
     * {@inheritDoc}
     * Specification:
     *   - This plugin check and remove inactive offers from cart.
     *   - Adds message after removing offer from cart.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preReloadItems(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->filterInactiveOfferItems($quoteTransfer);
    }
}
