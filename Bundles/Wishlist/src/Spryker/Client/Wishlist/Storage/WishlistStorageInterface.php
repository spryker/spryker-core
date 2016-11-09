<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist\Storage;

use Generated\Shared\Transfer\WishlistTransfer;

interface WishlistStorageInterface
{

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlist
     *
     * @return void
     */
    public function expandProductDetails(WishlistTransfer $wishlist);

}
