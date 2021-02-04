<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistExtension\Dependency\Plugin;

use Generated\Shared\Transfer\WishlistItemValidationResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

/**
 * This plugin interface can be extended in order to validate the items of a wishlist.
 */
interface WishlistItemsValidatorPluginInterface
{
    /**
     * Specification:
     * - Checks if the plugin is applicable for the given wishlist.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return bool
     */
    public function isApplicable(WishlistTransfer $wishlistTransfer): bool;

    /**
     * Specification:
     * - Validates the items of a wishlist and returns a transfer that contains a list of errors in case of a failed validation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemValidationResponseTransfer
     */
    public function validateItems(WishlistTransfer $wishlistTransfer): WishlistItemValidationResponseTransfer;
}
