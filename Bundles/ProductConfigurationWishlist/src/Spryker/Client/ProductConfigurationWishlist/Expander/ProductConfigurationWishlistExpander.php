<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationWishlist\Expander;

use Generated\Shared\Transfer\WishlistItemCollectionTransfer;
use Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer;
use Spryker\Client\ProductConfigurationWishlist\Dependency\Service\ProductConfigurationWishlistToProductConfigurationServiceInterface;

class ProductConfigurationWishlistExpander implements ProductConfigurationWishlistExpanderInterface
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
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $failedWishlistMoveToCartRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\WishlistItemCollectionTransfer $wishlistItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemCollectionTransfer
     */
    public function expandWishlistItemCollection(
        WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer,
        WishlistMoveToCartRequestCollectionTransfer $failedWishlistMoveToCartRequestCollectionTransfer,
        WishlistItemCollectionTransfer $wishlistItemCollectionTransfer
    ): WishlistItemCollectionTransfer {
        $failedProductConfigurationInstanceHashes = $this->collectWishlistItemProductConfigurationInstanceHashes(
            $failedWishlistMoveToCartRequestCollectionTransfer,
        );

        return $this->addValidWishlistItemsToCollection(
            $wishlistMoveToCartRequestCollectionTransfer,
            $wishlistItemCollectionTransfer,
            $failedProductConfigurationInstanceHashes,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer
     *
     * @return array<string>
     */
    protected function collectWishlistItemProductConfigurationInstanceHashes(
        WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer
    ): array {
        $productConfigurationInstanceHashes = [];

        foreach ($wishlistMoveToCartRequestCollectionTransfer->getRequests() as $wishlistMoveToCartRequestTransfer) {
            $wishlistItemTransfer = $wishlistMoveToCartRequestTransfer->getWishlistItemOrFail();
            if (!$wishlistItemTransfer->getProductConfigurationInstance()) {
                continue;
            }

            $productConfigurationInstanceHashes[] = $this->productConfigurationService->getProductConfigurationInstanceHash(
                $wishlistItemTransfer->getProductConfigurationInstanceOrFail(),
            );
        }

        return $productConfigurationInstanceHashes;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\WishlistItemCollectionTransfer $wishlistItemCollectionTransfer
     * @param array<string> $failedProductConfigurationInstanceHashes
     *
     * @return \Generated\Shared\Transfer\WishlistItemCollectionTransfer
     */
    protected function addValidWishlistItemsToCollection(
        WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer,
        WishlistItemCollectionTransfer $wishlistItemCollectionTransfer,
        array $failedProductConfigurationInstanceHashes
    ): WishlistItemCollectionTransfer {
        foreach ($wishlistMoveToCartRequestCollectionTransfer->getRequests() as $wishlistMoveToCartRequestTransfer) {
            $wishlistItemTransfer = $wishlistMoveToCartRequestTransfer->getWishlistItemOrFail();
            if (!$wishlistItemTransfer->getProductConfigurationInstance()) {
                $wishlistItemCollectionTransfer->addItem($wishlistItemTransfer);

                continue;
            }

            $productConfigurationInstanceHash = $this->productConfigurationService->getProductConfigurationInstanceHash(
                $wishlistItemTransfer->getProductConfigurationInstanceOrFail(),
            );
            if (in_array($productConfigurationInstanceHash, $failedProductConfigurationInstanceHashes, true)) {
                continue;
            }

            $wishlistItemCollectionTransfer->addItem($wishlistItemTransfer);
        }

        return $wishlistItemCollectionTransfer;
    }
}
