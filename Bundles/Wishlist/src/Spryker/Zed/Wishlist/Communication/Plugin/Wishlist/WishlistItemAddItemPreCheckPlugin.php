<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Communication\Plugin\Wishlist;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\WishlistExtension\Dependency\Plugin\AddItemPreCheckPluginInterface;

/**
 * @method \Spryker\Zed\Wishlist\Business\WishlistFacadeInterface getFacade()
 * @method \Spryker\Zed\Wishlist\WishlistConfig getConfig()
 * @method \Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Wishlist\Communication\WishlistCommunicationFactory getFactory()
 */
class WishlistItemAddItemPreCheckPlugin extends AbstractPlugin implements AddItemPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `WishlistItemTransfer.wishlistName` to be set.
     * - Requires `WishlistItemTransfer.fkCustomer` to be set.
     * - Validates wishlist item's wishlist before creation.
     * - Checks wishlist existence by `WishlistItemTransfer.wishlistName` and `WishlistItemTransfer.fkCustomer`.
     * - Returns `WishlistPreAddItemCheckResponseTransfer.isSuccess = true` if wishlist exists, is default or
     *   `WishlistItemTransfer.wishlistName` is an empty string.
     * - Returns `WishlistPreAddItemCheckResponseTransfer.isSuccess = false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer
     */
    public function check(WishlistItemTransfer $wishlistItemTransfer): WishlistPreAddItemCheckResponseTransfer
    {
        return $this->getFacade()->validateWishlistItemBeforeCreation($wishlistItemTransfer);
    }
}
