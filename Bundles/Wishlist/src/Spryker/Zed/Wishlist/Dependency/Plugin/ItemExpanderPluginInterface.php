<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Dependency\Plugin;

use Generated\Shared\Transfer\WishlistItemTransfer;

interface ItemExpanderPluginInterface
{
    /**
     * Specification:
     * - This plugin is executed when wishlist item is mapped from entity to transfer object
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $WishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandItem(WishlistItemTransfer $WishlistItemTransfer);
}
