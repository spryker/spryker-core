<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationWishlist\Communication\Plugin\Wishlist;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\WishlistExtension\Dependency\Plugin\WishlistPreUpdateItemPluginInterface;

/**
 * @method \Spryker\Zed\ProductConfigurationWishlist\Business\ProductConfigurationWishlistFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductConfigurationWishlist\ProductConfigurationWishlistConfig getConfig()
 */
class ProductConfigurationWishlistPreUpdateItemPlugin extends AbstractPlugin implements WishlistPreUpdateItemPluginInterface
{
    /**
     * {@inheritDoc}
     * - Prepares product configuration attached to a wishlist item to be saved.
     * - Does nothing if product configuration instance is not set at wishlist item.
     * - Sets JSON encoded product configuration instance to wishlist item otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function preUpdateItem(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer
    {
        return $this->getFacade()->expandWishlistItemWithProductConfigurationData($wishlistItemTransfer);
    }
}
