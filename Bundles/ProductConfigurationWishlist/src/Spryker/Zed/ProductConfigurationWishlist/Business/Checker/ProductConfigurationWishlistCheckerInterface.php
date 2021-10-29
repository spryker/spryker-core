<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationWishlist\Business\Checker;

use Generated\Shared\Transfer\WishlistTransfer;

interface ProductConfigurationWishlistCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return bool
     */
    public function hasConfigurableProductItems(WishlistTransfer $wishlistTransfer): bool;
}
