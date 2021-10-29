<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationWishlist\Communication\Plugin\Wishlist;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\WishlistExtension\Dependency\Plugin\AddItemPreCheckPluginInterface;

/**
 * @method \Spryker\Zed\ProductConfigurationWishlist\Business\ProductConfigurationWishlistFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductConfigurationWishlist\ProductConfigurationWishlistConfig getConfig()
 */
class ProductConfigurationWishlistAddItemPreCheckPlugin extends AbstractPlugin implements AddItemPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `WishlistItem.sku` to be set.
     * - Checks if product configuration exists by provided `WishlistItem.sku` transfer property.
     * - Returns `WishlistPreAddItemCheckResponseTransfer.success=true` if product configuration is found, sets `WishlistPreAddItemCheckResponseTransfer.success=false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer
     */
    public function check(WishlistItemTransfer $wishlistItemTransfer): WishlistPreAddItemCheckResponseTransfer
    {
        return $this->getFacade()->checkWishlistItemProductConfiguration($wishlistItemTransfer);
    }
}
