<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist\Storage;

use Generated\Shared\Transfer\WishlistOverviewResponseTransfer;

interface WishlistStorageInterface
{

    /**
     * @param \Generated\Shared\Transfer\WishlistOverviewResponseTransfer $wishlistResponseTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistOverviewResponseTransfer
     */
    public function expandProductDetails(WishlistOverviewResponseTransfer $wishlistResponseTransfer);

}
