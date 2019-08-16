<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Business\Wishlist;

use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;

interface WishlistReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistRequestTransfer $wishlistRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function getWishlistByUuid(WishlistRequestTransfer $wishlistRequestTransfer): WishlistResponseTransfer;
}
