<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationWishlist\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\ProductConfigurationWishlist\Dependency\Service\ProductConfigurationWishlistToUtilEncodingServiceInterface;

class ProductConfigurationWishlistItemExpander implements ProductConfigurationWishlistItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductConfigurationWishlist\Dependency\Service\ProductConfigurationWishlistToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductConfigurationWishlist\Dependency\Service\ProductConfigurationWishlistToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(ProductConfigurationWishlistToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItemWithProductConfigurationData(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer
    {
        $productConfigurationInstanceTransfer = $wishlistItemTransfer->getProductConfigurationInstance();
        if (!$productConfigurationInstanceTransfer) {
            return $wishlistItemTransfer;
        }

        $productConfigurationInstanceData = $this->utilEncodingService->encodeJson($productConfigurationInstanceTransfer->toArray());

        if ($productConfigurationInstanceData) {
            $wishlistItemTransfer->setProductConfigurationInstanceData($productConfigurationInstanceData);
        }

        return $wishlistItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItemWithProductConfiguration(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer
    {
        $productConfigurationInstanceData = $wishlistItemTransfer->getProductConfigurationInstanceData();

        if ($productConfigurationInstanceData === null) {
            return $wishlistItemTransfer;
        }

        $productConfigurationInstanceData = $this->utilEncodingService->decodeJson($productConfigurationInstanceData, true) ?? [];

        $productConfigurationInstance = (new ProductConfigurationInstanceTransfer())
            ->fromArray($productConfigurationInstanceData, true);

        return $wishlistItemTransfer->setProductConfigurationInstance($productConfigurationInstance);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function expandWishlistItemCollectionWithProductConfiguration(WishlistTransfer $wishlistTransfer): WishlistTransfer
    {
        $wishlistItemTransfers = [];
        foreach ($wishlistTransfer->getWishlistItems() as $wishlistItemTransfer) {
            $wishlistItemTransfers[] = $this->expandWishlistItemWithProductConfiguration($wishlistItemTransfer);
        }

        return $wishlistTransfer->setWishlistItems(
            new ArrayObject($wishlistItemTransfers),
        );
    }
}
