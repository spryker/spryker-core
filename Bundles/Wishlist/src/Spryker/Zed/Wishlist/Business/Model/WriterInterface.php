<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business\Model;

use Generated\Shared\Transfer\WishlistItemCollectionTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

interface WriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function createWishlist(WishlistTransfer $wishlistTransfer);

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function validateAndCreateWishlist(WishlistTransfer $wishlistTransfer);

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @throws \Spryker\Zed\Wishlist\Business\Exception\MissingWishlistException
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function updateWishlist(WishlistTransfer $wishlistTransfer);

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function validateAndUpdateWishlist(WishlistTransfer $wishlistTransfer);

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @throws \Spryker\Zed\Wishlist\Business\Exception\MissingWishlistException
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeWishlist(WishlistTransfer $wishlistTransfer);

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeWishlistByName(WishlistTransfer $wishlistTransfer);

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     * @param array $wishlistItemCollection
     *
     * @return void
     */
    public function addItemCollection(WishlistTransfer $wishlistTransfer, array $wishlistItemCollection);

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return void
     */
    public function emptyWishlist(WishlistTransfer $wishlistTransfer);

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function addItem(WishlistItemTransfer $wishlistItemTransfer);

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function removeItem(WishlistItemTransfer $wishlistItemTransfer);

    /**
     * @param \Generated\Shared\Transfer\WishlistItemCollectionTransfer $wishlistItemTransferCollection
     *
     * @return \Generated\Shared\Transfer\WishlistItemCollectionTransfer
     */
    public function removeItemCollection(WishlistItemCollectionTransfer $wishlistItemTransferCollection);
}
