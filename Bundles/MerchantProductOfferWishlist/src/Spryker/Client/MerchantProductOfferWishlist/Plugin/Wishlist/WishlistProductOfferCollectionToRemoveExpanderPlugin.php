<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferWishlist\Plugin\Wishlist;

use Generated\Shared\Transfer\WishlistItemCollectionTransfer;
use Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\WishlistExtension\Dependency\Plugin\WishlistCollectionToRemoveExpanderPluginInterface;

class WishlistProductOfferCollectionToRemoveExpanderPlugin extends AbstractPlugin implements WishlistCollectionToRemoveExpanderPluginInterface
{
    /**
     * {@inheritDoc}
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
        $failedProductOfferReference = [];

        foreach ($failedWishlistMoveToCartRequestCollectionTransfer->getRequests() as $failedMoveToCartRequestTransfer) {
            $failedProductOfferReference[] = $failedMoveToCartRequestTransfer->getProductOfferReference();
        }

        foreach ($wishlistMoveToCartRequestCollectionTransfer->getRequests() as $moveToCartRequestTransfer) {
            if (in_array($moveToCartRequestTransfer->getProductOfferReference(), $failedProductOfferReference)) {
                continue;
            }

            /** @var \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer */
            $wishlistItemTransfer = $moveToCartRequestTransfer->getWishlistItem();
            $wishlistItemCollectionTransfer->addItem($wishlistItemTransfer);
        }

        return $wishlistItemCollectionTransfer;
    }
}
