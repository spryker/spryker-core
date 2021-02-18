<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferWishlist\Plugin\Wishlist;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\WishlistExtension\Dependency\Plugin\WishlistPostMoveToCartCollectionExpanderPluginInterface;

class WishlistProductOfferPostMoveToCartCollectionExpanderPlugin extends AbstractPlugin implements WishlistPostMoveToCartCollectionExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands WishlistMoveToCartRequestCollection transfer object with not valid product offers as request items.
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
        $productOfferReferenceIndex = [];
        $merchantReferenceIndex = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getMerchantReference() && !$itemTransfer->getProductOfferReference()) {
                continue;
            }

            $productOfferReferenceIndex[$itemTransfer->getProductOfferReference()] = true;
            $merchantReferenceIndex[$itemTransfer->getMerchantReference()] = true;
        }

        foreach ($quoteTransfer->getBundleItems() as $itemTransfer) {
            if (!$itemTransfer->getMerchantReference() && !$itemTransfer->getProductOfferReference()) {
                continue;
            }

            $productOfferReferenceIndex[$itemTransfer->getProductOfferReference()] = true;
            $merchantReferenceIndex[$itemTransfer->getMerchantReference()] = true;
        }

        if (!$productOfferReferenceIndex && !$merchantReferenceIndex) {
            return $wishlistMoveToCartRequestCollectionDiffTransfer;
        }

        foreach ($wishlistMoveToCartRequestCollectionTransfer->getRequests() as $wishlistMoveToCartRequestTransfer) {
            if (
                isset($productOfferReferenceIndex[$wishlistMoveToCartRequestTransfer->getProductOfferReference()]) ||
                isset($merchantReferenceIndex[$wishlistMoveToCartRequestTransfer->getMerchantReference()])
            ) {
                continue;
            }

            $wishlistMoveToCartRequestCollectionDiffTransfer->addRequest($wishlistMoveToCartRequestTransfer);
        }

        return $wishlistMoveToCartRequestCollectionDiffTransfer;
    }
}
