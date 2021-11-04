<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationWishlist\Plugin\Wishlist;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\WishlistExtension\Dependency\Plugin\WishlistPostMoveToCartCollectionExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductConfigurationWishlist\ProductConfigurationWishlistClientInterface getClient()
 */
class ProductConfigurationWishlistPostMoveToCartCollectionExpanderPlugin extends AbstractPlugin implements WishlistPostMoveToCartCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `WishlistMoveToCartRequestCollectionTransfer::requests::wishlistItem` to be set.
     * - Expands `WishlistMoveToCartRequestCollectionTransfer` with not valid product configuration items.
     * - Returns expanded `WishlistMoveToCartRequestCollectionTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionDiffTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer
     */
    public function expand(
        WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer,
        QuoteTransfer $quoteTransfer,
        WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionDiffTransfer
    ): WishlistMoveToCartRequestCollectionTransfer {
        return $this->getClient()
            ->expandWishlistMoveToCartRequestCollection(
                $wishlistMoveToCartRequestCollectionTransfer,
                $quoteTransfer,
                $wishlistMoveToCartRequestCollectionDiffTransfer,
            );
    }
}
