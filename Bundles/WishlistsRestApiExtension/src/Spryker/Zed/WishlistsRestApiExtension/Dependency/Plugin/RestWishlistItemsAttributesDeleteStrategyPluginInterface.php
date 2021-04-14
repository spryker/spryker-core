<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApiExtension\Dependency\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;

/**
 * Provides ability to remove wishlist items.
 */
interface RestWishlistItemsAttributesDeleteStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if plugin is applicable for a given `WishlistItemRequest` transfer object and collection of `WishlistItem` transfer objects.
     *
     * @api
     *
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\WishlistItemTransfer[] $wishlistItemTransfers
     *
     * @return bool
     */
    public function isApplicable(WishlistItemRequestTransfer $wishlistItemRequestTransfer, ArrayObject $wishlistItemTransfers): bool;

    /**
     * Specification:
     * - Deletes Wishlist item by given `WishlistItemRequest` transfer object and collection of `WishlistItem` transfer objects.
     *
     * @api
     *
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\WishlistItemTransfer[] $wishlistItemTransfers
     *
     * @return void
     */
    public function delete(WishlistItemRequestTransfer $wishlistItemRequestTransfer, ArrayObject $wishlistItemTransfers): void;
}
