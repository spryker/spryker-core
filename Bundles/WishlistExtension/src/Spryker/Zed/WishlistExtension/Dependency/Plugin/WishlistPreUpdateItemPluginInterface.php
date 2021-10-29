<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistExtension\Dependency\Plugin;

use Generated\Shared\Transfer\WishlistItemTransfer;

/**
 * Executed before the update of a `WishlistItem` in persistence storage.
 */
interface WishlistPreUpdateItemPluginInterface
{
    /**
     * Specification:
     * - Executed before the update of a `WishlistItem` in persistence storage.
     * - Prepares `WishlistItem` item to be saved.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function preUpdateItem(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer;
}
