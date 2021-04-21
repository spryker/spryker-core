<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Business\WishlistValidator;

use Generated\Shared\Transfer\ValidationResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

interface MerchantWishlistValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validateItems(WishlistTransfer $wishlistTransfer): ValidationResponseTransfer;
}
