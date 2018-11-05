<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Persistence\Mapper;

use Generated\Shared\Transfer\WishlistTransfer;

interface WishlistMapperInterface
{
    /**
     * @param array $wishlist
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function mapWishlistEntityToWishlistTransfer(array $wishlist): WishlistTransfer;
}
