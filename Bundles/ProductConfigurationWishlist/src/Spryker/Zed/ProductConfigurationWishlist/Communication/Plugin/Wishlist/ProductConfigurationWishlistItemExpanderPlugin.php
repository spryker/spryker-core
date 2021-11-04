<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationWishlist\Communication\Plugin\Wishlist;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\WishlistExtension\Dependency\Plugin\WishlistItemExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductConfigurationWishlist\Business\ProductConfigurationWishlistFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductConfigurationWishlist\ProductConfigurationWishlistConfig getConfig()
 */
class ProductConfigurationWishlistItemExpanderPlugin extends AbstractPlugin implements WishlistItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `WishlistItem` transfer object with product configuration data.
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
        return $this->getFacade()->expandWishlistItemWithProductConfiguration($wishlistItemTransfer);
    }
}
