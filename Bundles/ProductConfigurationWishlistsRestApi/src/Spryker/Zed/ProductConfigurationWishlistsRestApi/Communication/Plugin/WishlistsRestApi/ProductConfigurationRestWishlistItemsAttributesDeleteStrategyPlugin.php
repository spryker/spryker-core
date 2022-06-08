<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationWishlistsRestApi\Communication\Plugin\WishlistsRestApi;

use ArrayObject;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\WishlistsRestApiExtension\Dependency\Plugin\RestWishlistItemsAttributesDeleteStrategyPluginInterface;

/**
 * @method \Spryker\Zed\ProductConfigurationWishlistsRestApi\Business\ProductConfigurationWishlistsRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductConfigurationWishlistsRestApi\ProductConfigurationWishlistsRestApiConfig getConfig()
 */
class ProductConfigurationRestWishlistItemsAttributesDeleteStrategyPlugin extends AbstractPlugin implements RestWishlistItemsAttributesDeleteStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `WishlistItemRequestTransfer::sku` to be provided.
     * - Finds an item by product sku + product configuration instance hash in collection of `WishlistItem` transfer objects.
     * - Returns true if wishlist item with product configuration was found, false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @return bool
     */
    public function isApplicable(
        WishlistItemRequestTransfer $wishlistItemRequestTransfer,
        ArrayObject $wishlistItemTransfers
    ): bool {
        return (bool)$this
            ->getFacade()
            ->findWishlistItemByProductConfiguration($wishlistItemRequestTransfer, $wishlistItemTransfers);
    }

    /**
     * {@inheritDoc}
     * - Requires `WishlistItemRequestTransfer::sku` to be provided.
     * - Finds an item by product sku + product configuration instance hash in collection of `WishlistItem` transfer objects.
     * - Deletes found wishlist item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @return void
     */
    public function delete(
        WishlistItemRequestTransfer $wishlistItemRequestTransfer,
        ArrayObject $wishlistItemTransfers
    ): void {
        $this->getFacade()->deleteWishlistItem($wishlistItemRequestTransfer, $wishlistItemTransfers);
    }
}
