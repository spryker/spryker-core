<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistExtension\Dependency\Plugin;

use Generated\Shared\Transfer\WishlistItemTransfer;

/**
 * Provides ability to expand wishlist items with adittional data.
 */
interface WishlistItemExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands WishlistItemTransfer with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expand(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer;
}
