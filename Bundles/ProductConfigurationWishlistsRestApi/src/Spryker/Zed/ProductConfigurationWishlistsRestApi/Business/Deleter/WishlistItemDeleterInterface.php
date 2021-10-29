<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationWishlistsRestApi\Business\Deleter;

use ArrayObject;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;

interface WishlistItemDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @return void
     */
    public function deleteWishlistItem(
        WishlistItemRequestTransfer $wishlistItemRequestTransfer,
        ArrayObject $wishlistItemTransfers
    ): void;
}
