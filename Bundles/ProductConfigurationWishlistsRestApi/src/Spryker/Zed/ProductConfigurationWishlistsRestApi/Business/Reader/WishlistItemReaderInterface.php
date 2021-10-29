<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationWishlistsRestApi\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;

interface WishlistItemReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer|null
     */
    public function findWishlistItemByProductConfiguration(
        WishlistItemRequestTransfer $wishlistItemRequestTransfer,
        ArrayObject $wishlistItemTransfers
    ): ?WishlistItemTransfer;
}
