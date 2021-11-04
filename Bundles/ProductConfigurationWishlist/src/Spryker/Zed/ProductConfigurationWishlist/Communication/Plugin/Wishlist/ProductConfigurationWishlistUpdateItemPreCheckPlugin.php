<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationWishlist\Communication\Plugin\Wishlist;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\WishlistExtension\Dependency\Plugin\UpdateItemPreCheckPluginInterface;

/**
 * @method \Spryker\Zed\ProductConfigurationWishlist\Business\ProductConfigurationWishlistFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductConfigurationWishlist\ProductConfigurationWishlistConfig getConfig()
 */
class ProductConfigurationWishlistUpdateItemPreCheckPlugin extends AbstractPlugin implements UpdateItemPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `WishlistItem.sku` to be set.
     * - Checks if product configuration exists by provided `WishlistItem.sku` transfer property.
     * - Returns `WishlistPreUpdateItemCheckResponseTransfer.success=true` if product configuration is found, sets `WishlistPreUpdateItemCheckResponseTransfer.success=false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer
     */
    public function check(WishlistItemTransfer $wishlistItemTransfer): WishlistPreUpdateItemCheckResponseTransfer
    {
        return $this->getFacade()->checkUpdateWishlistItemProductConfiguration($wishlistItemTransfer);
    }
}
