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
    public function storeAction(WishlistChangeInterface $changeTransfer)
    {
        return $this->getFacade()->storeItems($changeTransfer);
    }

    /**
     * @param WishlistChangeInterface $changeTransfer
     *
     * @return WishlistInterface
     */
    public function groupAction(WishlistChangeInterface $changeTransfer)
    {
        return $this->getFacade()->groupAddedItems($changeTransfer);
    }

    /**
     * @param WishlistChangeInterface $changeTransfer
     *
     * @return WishlistInterface
     */
    public function ungroupAction(WishlistChangeInterface $changeTransfer)
    {
        return $this->getFacade()->ungroupRemovedItems($changeTransfer);
    }



    /**
     * @param WishlistItemInterface $itemTransfer
     *
     * @return int
     */
    public function removeAction(WishlistChangeInterface $changeTransfer)
    {
        return $this->getFacade()->removeItem($changeTransfer);
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
