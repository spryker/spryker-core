<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist\Session;

use Generated\Shared\Transfer\WishlistTransfer;

interface WishlistSessionInterface
{

    /**
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getWishlist();

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlist
     *
     * @return $this
     */
    public function setWishlist(WishlistTransfer $wishlist);

}
