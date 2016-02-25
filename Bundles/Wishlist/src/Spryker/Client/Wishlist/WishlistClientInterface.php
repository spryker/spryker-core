<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist;

use Generated\Shared\Transfer\ItemTransfer;

/**
 * @method \Spryker\Client\Wishlist\WishlistFactory getFactory()
 */
interface WishlistClientInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $wishlistItem
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function addItem(ItemTransfer $wishlistItem);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $wishlistItem
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function increaseItemQuantity(ItemTransfer $wishlistItem);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $wishlistItem
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function decreaseItemQuantity(ItemTransfer $wishlistItem);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $wishlistItem
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeItem(ItemTransfer $wishlistItem);

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getWishlist();

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function synchronizeSession();

}
