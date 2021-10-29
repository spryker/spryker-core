<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductWishlist\Communication\Plugin\Wishlist;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\WishlistExtension\Dependency\Plugin\WishlistPreUpdateItemPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductWishlist\MerchantProductWishlistConfig getConfig()
 * @method \Spryker\Zed\MerchantProductWishlist\Communication\MerchantProductWishlistCommunicationFactory getFactory()
 */
class WishlistMerchantProductPreUpdateItemPlugin extends AbstractPlugin implements WishlistPreUpdateItemPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `WishlistItemTransfer.sku` to be set.
     * - Expects `WishlistItemTransfer.merchantReference` to be unset.
     * - Finds merchant from Persistence by sku.
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
            ->createMerchantProductWishlistItemExpander()
            ->expandWishlistItem($wishlistItemTransfer);
    }
}
