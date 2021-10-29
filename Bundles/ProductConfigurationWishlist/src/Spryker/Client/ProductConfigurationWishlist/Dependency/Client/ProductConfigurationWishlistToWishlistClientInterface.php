<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationWishlist\Dependency\Client;

use Generated\Shared\Transfer\WishlistItemCriteriaTransfer;
use Generated\Shared\Transfer\WishlistItemResponseTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;

interface ProductConfigurationWishlistToWishlistClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistItemCriteriaTransfer $wishlistItemCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function getWishlistItem(WishlistItemCriteriaTransfer $wishlistItemCriteriaTransfer): WishlistItemResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function updateWishlistItem(WishlistItemTransfer $wishlistItemTransfer): WishlistItemResponseTransfer;
}
