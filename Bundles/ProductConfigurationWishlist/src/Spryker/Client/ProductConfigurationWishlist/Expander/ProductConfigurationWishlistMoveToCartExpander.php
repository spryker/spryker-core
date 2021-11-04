<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationWishlist\Expander;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer;
use Spryker\Client\ProductConfigurationWishlist\Dependency\Service\ProductConfigurationWishlistToProductConfigurationServiceInterface;

class ProductConfigurationWishlistMoveToCartExpander implements ProductConfigurationWishlistMoveToCartExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationWishlist\Dependency\Service\ProductConfigurationWishlistToProductConfigurationServiceInterface
     */
    protected $productConfigurationService;

    /**
     * @param \Spryker\Client\ProductConfigurationWishlist\Dependency\Service\ProductConfigurationWishlistToProductConfigurationServiceInterface $productConfigurationService
     */
    public function __construct(ProductConfigurationWishlistToProductConfigurationServiceInterface $productConfigurationService)
    {
        $this->productConfigurationService = $productConfigurationService;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionDiffTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer
     */
    public function expandWishlistMoveToCartRequestCollection(
        WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer,
        QuoteTransfer $quoteTransfer,
        WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionDiffTransfer
    ): WishlistMoveToCartRequestCollectionTransfer {
        $quoteProductConfigurationInstanceHashes = $this->collectQuoteItemProductConfigurationInstanceHashes($quoteTransfer);
        if (!$quoteProductConfigurationInstanceHashes) {
            return $wishlistMoveToCartRequestCollectionDiffTransfer;
        }

        return $this->addMissedWishlistItemRequestsToCollection(
            $wishlistMoveToCartRequestCollectionTransfer,
            $wishlistMoveToCartRequestCollectionDiffTransfer,
            $quoteProductConfigurationInstanceHashes,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string>
     */
    protected function collectQuoteItemProductConfigurationInstanceHashes(QuoteTransfer $quoteTransfer): array
    {
        $quoteProductConfigurationInstancesHashes = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getProductConfigurationInstance()) {
                continue;
            }

            $quoteProductConfigurationInstancesHashes[] = $this->productConfigurationService->getProductConfigurationInstanceHash(
                $itemTransfer->getProductConfigurationInstanceOrFail(),
            );
        }

        return $quoteProductConfigurationInstancesHashes;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionDiffTransfer
     * @param array<string> $productConfigurationInstanceHashes
     *
     * @return \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer
     */
    protected function addMissedWishlistItemRequestsToCollection(
        WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer,
        WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionDiffTransfer,
        array $productConfigurationInstanceHashes
    ): WishlistMoveToCartRequestCollectionTransfer {
        foreach ($wishlistMoveToCartRequestCollectionTransfer->getRequests() as $wishlistMoveToCartRequestTransfer) {
            $wishlistItemTransfer = $wishlistMoveToCartRequestTransfer->getWishlistItemOrFail();
            if (!$wishlistItemTransfer->getProductConfigurationInstance()) {
                continue;
            }

            $productConfigurationInstanceTransfer = $this->productConfigurationService->getProductConfigurationInstanceHash(
                $wishlistItemTransfer->getProductConfigurationInstanceOrFail(),
            );
            if (in_array($productConfigurationInstanceTransfer, $productConfigurationInstanceHashes, true)) {
                continue;
            }

            $wishlistMoveToCartRequestCollectionDiffTransfer->addRequest($wishlistMoveToCartRequestTransfer);
        }

        return $wishlistMoveToCartRequestCollectionDiffTransfer;
    }
}
