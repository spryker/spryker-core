<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationWishlist\Plugin\Wishlist;

use Generated\Shared\Transfer\WishlistItemCollectionTransfer;
use Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\WishlistExtension\Dependency\Plugin\WishlistCollectionToRemoveExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductConfigurationWishlist\ProductConfigurationWishlistClientInterface getClient()
 */
class ProductConfigurationWishlistCollectionToRemoveExpanderPlugin extends AbstractPlugin implements WishlistCollectionToRemoveExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `WishlistMoveToCartRequestCollectionTransfer::requests::wishlistItem` to be set.
     * - Expands `WishlistItemCollectionTransfer` with successfully added wishlist items to a cart.
     * - Returns expanded `WishlistItemCollectionTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $failedWishlistMoveToCartRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\WishlistItemCollectionTransfer $wishlistItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemCollectionTransfer
     */
    public function expand(
        WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer,
        WishlistMoveToCartRequestCollectionTransfer $failedWishlistMoveToCartRequestCollectionTransfer,
        WishlistItemCollectionTransfer $wishlistItemCollectionTransfer
    ): WishlistItemCollectionTransfer {
        return $this->getClient()
            ->expandWishlistItemCollection(
                $wishlistMoveToCartRequestCollectionTransfer,
                $failedWishlistMoveToCartRequestCollectionTransfer,
                $wishlistItemCollectionTransfer,
            );
    }
}
