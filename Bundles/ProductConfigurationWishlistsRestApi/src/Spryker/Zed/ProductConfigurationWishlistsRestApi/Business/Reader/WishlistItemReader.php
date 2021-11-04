<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationWishlistsRestApi\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Zed\ProductConfigurationWishlistsRestApi\Dependency\Service\ProductConfigurationWishlistsRestApiToProductConfigurationServiceInterface;

class WishlistItemReader implements WishlistItemReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductConfigurationWishlistsRestApi\Dependency\Service\ProductConfigurationWishlistsRestApiToProductConfigurationServiceInterface
     */
    protected $productConfigurationService;

    /**
     * @param \Spryker\Zed\ProductConfigurationWishlistsRestApi\Dependency\Service\ProductConfigurationWishlistsRestApiToProductConfigurationServiceInterface $productConfigurationService
     */
    public function __construct(ProductConfigurationWishlistsRestApiToProductConfigurationServiceInterface $productConfigurationService)
    {
        $this->productConfigurationService = $productConfigurationService;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer|null
     */
    public function findWishlistItemByProductConfiguration(
        WishlistItemRequestTransfer $wishlistItemRequestTransfer,
        ArrayObject $wishlistItemTransfers
    ): ?WishlistItemTransfer {
        foreach ($wishlistItemTransfers as $wishlistItemTransfer) {
            if (!$wishlistItemTransfer->getProductConfigurationInstance()) {
                continue;
            }

            $productConfigurationInstanceHash = $this->productConfigurationService->getProductConfigurationInstanceHash(
                $wishlistItemTransfer->getProductConfigurationInstanceOrFail(),
            );

            $uuid = sprintf('%s_%s', $wishlistItemTransfer->getSkuOrFail(), $productConfigurationInstanceHash);

            if ($wishlistItemRequestTransfer->getUuid() === $uuid) {
                return $wishlistItemTransfer;
            }
        }

        return null;
    }
}
