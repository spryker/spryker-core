<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist\Zed;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistItemCollectionTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistOverviewRequestTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

interface WishlistStubInterface
{

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function createWishlist(WishlistTransfer $wishlistTransfer);

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function validateAndCreateWishlist(WishlistTransfer $wishlistTransfer);

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function updateWishlist(WishlistTransfer $wishlistTransfer);

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function validateAndUpdateWishlist(WishlistTransfer $wishlistTransfer);

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function removeWishlist(WishlistTransfer $wishlistTransfer);

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function removeWishlistByName(WishlistTransfer $wishlistTransfer);

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function addItem(WishlistItemTransfer $wishlistItemTransfer);

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function removeItem(WishlistItemTransfer $wishlistItemTransfer);

    /**
     * @param \Generated\Shared\Transfer\WishlistItemCollectionTransfer $wishlistItemTransferCollection
     *
     * @return \Generated\Shared\Transfer\WishlistItemCollectionTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function removeItemCollection(WishlistItemCollectionTransfer $wishlistItemTransferCollection);

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getWishlist(WishlistTransfer $wishlistTransfer);

    /**
     * @param \Generated\Shared\Transfer\WishlistOverviewRequestTransfer $wishlistOverviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistOverviewResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getWishlistOverview(WishlistOverviewRequestTransfer $wishlistOverviewRequestTransfer);

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistCollectionTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getCustomerWishlistCollection(CustomerTransfer $customerTransfer);

}
