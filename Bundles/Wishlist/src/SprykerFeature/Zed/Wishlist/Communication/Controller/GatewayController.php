<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Communication\Controller;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use Generated\Shared\Wishlist\WishlistItemInterface;
use SprykerFeature\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use SprykerFeature\Zed\Wishlist\Business\WishlistFacade;

/**
 * @method WishlistFacade  getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return WishlistInterface
     */
    public function getWishlistAction(CustomerInterface $customerTransfer)
    {
        return $this->getFacade()->getWishlist($customerTransfer);
    }

    /**
     * @param WishlistChangeInterface $changeTransfer
     */
    public function saveAction(WishlistChangeInterface $changeTransfer)
    {
        return $this->getFacade()->saveItems($changeTransfer);
    }

    /**
     * @param WishlistItemInterface $itemTransfer
     *
     * @return int
     */
    public function removeAction(WishlistItemInterface $itemTransfer)
    {
        return $this->getFacade()->removeItem($itemTransfer);
    }


    /**
     * @param WishlistInterface $wishlistTransfer
     *
     * @return WishlistInterface
     */
    public function mergeAction(WishlistInterface $wishlistTransfer)
    {
        return $this->getFacade()->mergeWishlist($wishlistTransfer);
    }

}
