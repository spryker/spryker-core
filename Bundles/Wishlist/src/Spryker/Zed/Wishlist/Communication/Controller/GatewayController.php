<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Communication\Controller;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Wishlist\Business\WishlistFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function createWishlist(WishlistTransfer $wishlistTransfer)
    {
        return $this->getFacade()->createWishlist($wishlistTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function updateWishlist(WishlistTransfer $wishlistTransfer)
    {
        return $this->getFacade()->updateWishlist($wishlistTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeWishlist(WishlistTransfer $wishlistTransfer)
    {
        return $this->getFacade()->removeWishlist($wishlistTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $WishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function addItemAction(WishlistItemTransfer $WishlistItemTransfer)
    {
        return $this->getFacade()->addItem($WishlistItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $WishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function removeItemAction(WishlistItemTransfer $WishlistItemTransfer)
    {
        return $this->getFacade()->removeItem($WishlistItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getCustomerWishlistAction(WishlistTransfer $wishlistTransfer)
    {
        return $this->getFacade()->getCustomerWishlistByName($wishlistTransfer);
    }

}
