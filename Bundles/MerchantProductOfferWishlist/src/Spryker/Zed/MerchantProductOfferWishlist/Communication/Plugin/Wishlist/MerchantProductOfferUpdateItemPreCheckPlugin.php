<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferWishlist\Communication\Plugin\Wishlist;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\WishlistExtension\Dependency\Plugin\UpdateItemPreCheckPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductOfferWishlist\MerchantProductOfferWishlistConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOfferWishlist\Communication\MerchantProductOfferWishlistCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProductOfferWishlist\Business\MerchantProductOfferWishlistFacadeInterface getFacade()
 */
class MerchantProductOfferUpdateItemPreCheckPlugin extends AbstractPlugin implements UpdateItemPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Gets product offer collection by `WishlistItem.sku` transfer property.
     * - Checks if product offer exists in collection by `WishlistItem.productOfferReference` transfer object.
     * - Returns `WishlistPreUpdateItemCheckResponseTransfer.success=true` if product offer found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer
     */
    public function check(WishlistItemTransfer $wishlistItemTransfer): WishlistPreUpdateItemCheckResponseTransfer
    {
        return $this->getFacade()->checkUpdateWishlistItemProductOfferRelation($wishlistItemTransfer);
    }
}
