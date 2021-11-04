<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationWishlist\Communication\Plugin\Wishlist;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Wishlist\Dependency\Plugin\ItemExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductConfigurationWishlist\Business\ProductConfigurationWishlistFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductConfigurationWishlist\ProductConfigurationWishlistConfig getConfig()
 */
class ProductConfigurationItemExpanderPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `WishlistItem` transfer object with product configuration data.
     * - Returns expanded `WishlistItem` transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $WishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandItem(WishlistItemTransfer $WishlistItemTransfer): WishlistItemTransfer
    {
        return $this->getFacade()->expandWishlistItemWithProductConfiguration($WishlistItemTransfer);
    }
}
