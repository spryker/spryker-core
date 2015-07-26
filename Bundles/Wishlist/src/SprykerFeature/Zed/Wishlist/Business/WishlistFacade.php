<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use Generated\Shared\Wishlist\WishlistItemInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method WishlistDependencyContainer getDependencyContainer()
 */
class WishlistFacade extends AbstractFacade
{

    /**
     * @param WishlistChangeInterface $changeTransfer
     */
    public function saveItems(WishlistChangeInterface $changeTransfer)
    {
        $this->getDependencyContainer()
            ->createEntityIntegrator()
            ->saveItems($changeTransfer);
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return WishlistInterface
     */
    public function getWishlist(CustomerInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createTransferObjectIntegrator()
            ->getWishlistTransfer($customerTransfer);
    }

    /**
     * @param WishlistItemInterface $wishlistItemTransfer
     *
     * @return WishlistInterface
     */
    public function getWishlistItemQuery(WishlistItemInterface $wishlistItemTransfer)
    {
        return $this->getDependencyContainer()
            ->createTransferObjectIntegrator()
            ->getWishlistTransfer($wishlistItemTransfer);

    }

    /**
     * @param WishlistItemInterface $wishlistItemTransfer
     *
     * @return int
     */
    public function removeItem(WishlistItemInterface $wishlistItemTransfer)
    {
        return  $this->getDependencyContainer()
            ->createEntityIntegrator()
            ->removeItem($wishlistItemTransfer);
    }

    public function mergeWishlist(WishlistInterface $wishlist)
    {
        return $this->getDependencyContainer()
            ->createTransferObjectIntegrator()
            ->mergeWishlist($wishlist);
    }


}
