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
class ValidMerchantProductOfferUpdateItemPreCheckPlugin extends AbstractPlugin implements UpdateItemPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `WishlistItem.sku` and `WishlistItem.merchantReference` transfer properties to be set if `WishlistItem.productOfferReference` is set.
     * - Checks that product offer belongs to the item with specified SKU.
     * - Checks that product offer belongs to the specified merchant.
     * - Finds an active and approved product offer by `WishlistItem.sku` and `WishlistItem.productOfferReference` transfer properties.
     * - Finds an active and approved merchant by `ProductOffer.merchantReference` transfer property.
     * - Returns `WishlistPreUpdateItemCheckResponseTransfer.success=true` if the corresponding product offer and merchant found.
     * - Returns `WishlistPreUpdateItemCheckResponseTransfer.success=false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer
     */
    public function check(WishlistItemTransfer $wishlistItemTransfer): WishlistPreUpdateItemCheckResponseTransfer
    {
        return $this->getFacade()->validateWishlistItemProductOfferBeforeUpdate($wishlistItemTransfer);
    }
}
