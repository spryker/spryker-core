<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Communication\Plugin\Wishlist;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\WishlistExtension\Dependency\Plugin\WishlistItemExpanderPluginInterface;

/**
 * This plugin should be put after `\Spryker\Zed\PriceProduct\Communication\Plugin\Wishlist\PriceProductWishlistItemExpanderPlugin` in the plugin stack, otherwise prices for offers will not be correct.
 *
 * @method \Spryker\Zed\PriceProductOffer\PriceProductOfferConfig getConfig()
 * @method \Spryker\Zed\PriceProductOffer\Business\PriceProductOfferFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductOffer\Communication\PriceProductOfferCommunicationFactory getFactory()
 */
class PriceProductOfferWishlistItemExpanderPlugin extends AbstractPlugin implements WishlistItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `WishlistItem` transfer object with prices.
     * - Returns expanded `WishlistItem` transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expand(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer
    {
        return $this->getFacade()->expandWishlistItemWithPrices($wishlistItemTransfer);
    }
}
