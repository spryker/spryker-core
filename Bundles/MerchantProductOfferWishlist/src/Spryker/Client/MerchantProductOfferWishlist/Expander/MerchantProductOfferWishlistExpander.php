<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferWishlist\Expander;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\WishlistItemCollectionTransfer;
use Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer;

class MerchantProductOfferWishlistExpander implements MerchantProductOfferWishlistExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $failedWishlistMoveToCartRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\WishlistItemCollectionTransfer $wishlistItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemCollectionTransfer
     */
    public function expandWishlistItemCollectionTransfer(
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

    /**
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionDiffTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer
     */
    public function expandWishlistMoveToCartRequestCollectionTransfer(
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
