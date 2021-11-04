<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferWishlist\Communication\Plugin\Wishlist;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\WishlistExtension\Dependency\Plugin\WishlistPreUpdateItemPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductOfferWishlist\MerchantProductOfferWishlistConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOfferWishlist\Business\MerchantProductOfferWishlistFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductOfferWishlist\Communication\MerchantProductOfferWishlistCommunicationFactory getFactory()
 */
class WishlistProductOfferPreUpdateItemPlugin extends AbstractPlugin implements WishlistPreUpdateItemPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `WishlistItemTransfer.productOfferReference` to be set.
     * - Finds product offer from Persistence by `WishlistItemTransfer.productOfferReference`.
     * - Sets `WishlistItemTransfer.merchantReference`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function preUpdateItem(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer
    {
        return $this->getFactory()
            ->createMerchantProductOfferWishlistItemExpander()
            ->expandWishlistItem($wishlistItemTransfer);
    }
}
