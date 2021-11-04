<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationWishlist\Communication\Plugin\Wishlist;

use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\WishlistExtension\Dependency\Plugin\WishlistReloadItemsPluginInterface;

/**
 * @method \Spryker\Zed\ProductConfigurationWishlist\Business\ProductConfigurationWishlistFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductConfigurationWishlist\ProductConfigurationWishlistConfig getConfig()
 */
class ProductConfigurationWishlistReloadItemsPlugin extends AbstractPlugin implements WishlistReloadItemsPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if `Wishlist` transfer object contains configurable product items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return bool
     */
    public function isApplicable(WishlistTransfer $wishlistTransfer): bool
    {
        return $this->getFacade()->hasConfigurableProductItems($wishlistTransfer);
    }

    /**
     * {@inheritDoc}
     * - Expands `WishlistItem` transfers collection in `Wishlist` transfer object with product configuration data.
     * - Returns expanded `Wishlist` transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function reloadItems(WishlistTransfer $wishlistTransfer): WishlistTransfer
    {
        return $this->getFacade()->expandWishlistItemCollectionWithProductConfiguration($wishlistTransfer);
    }
}
