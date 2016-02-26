<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;

interface WishlistFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function addItem(WishlistChangeTransfer $wishlistChange);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeItem(WishlistChangeTransfer $wishlistChange);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function decreaseQuantity(WishlistChangeTransfer $wishlistChange);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistChange
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function increaseQuantity(WishlistChangeTransfer $wishlistChange);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getCustomerWishlist(CustomerTransfer $customerTransfer);

}
