<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Persistence;

use Generated\Shared\Transfer\WishlistCollectionTransfer;
use Generated\Shared\Transfer\WishlistFilterTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

interface WishlistRepositoryInterface
{
    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\WishlistCollectionTransfer
     */
    public function getByCustomerReference(string $customerReference): WishlistCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\WishlistFilterTransfer $wishlistFilterTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer|null
     */
    public function findWishlistByFilter(WishlistFilterTransfer $wishlistFilterTransfer): ?WishlistTransfer;
}
