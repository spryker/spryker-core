<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Wishlist\Business\WishlistBusinessFactory getFactory()
 */
class WishlistFacade extends AbstractFacade implements WishlistFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function createWishlist(WishlistTransfer $wishlistTransfer)
    {
        return $this->getFactory()
            ->createWriter()
            ->createWishlist($wishlistTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function updateWishlist(WishlistTransfer $wishlistTransfer)
    {
        return $this->getFactory()
            ->createWriter()
            ->updateWishlist($wishlistTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeWishlist(WishlistTransfer $wishlistTransfer)
    {
        return $this->getFactory()
            ->createWriter()
            ->removeWishlist($wishlistTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function addItemCollection(WishlistTransfer $wishlistTransfer)
    {
        return $this->getFactory()
            ->createWriter()
            ->addItemCollection($wishlistTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeItemCollection(WishlistTransfer $wishlistTransfer)
    {
        return $this->getFactory()
            ->createWriter()
            ->removeItemCollection($wishlistTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function addItem(WishlistItemTransfer $wishlistItemTransfer)
    {
        return $this->getFactory()
            ->createWriter()
            ->addItem($wishlistItemTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function removeItem(WishlistItemTransfer $wishlistItemTransfer)
    {
        return $this->getFactory()
            ->createWriter()
            ->removeItem($wishlistItemTransfer);
    }

    /**
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getCustomerWishlist($idCustomer)
    {
        return $this->getFactory()
            ->createReader()
            ->getWishlist($idCustomer);
    }

}
