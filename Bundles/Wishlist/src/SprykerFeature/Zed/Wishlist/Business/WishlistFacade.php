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
     *
     * @return WishlistInterface
     */
    public function groupAddedItems(WishlistChangeInterface $changeTransfer)
    {
        return $this->getDependencyContainer()
            ->createTransferObjectIntegrator()
            ->groupAddedItems($changeTransfer);
    }

    /**
     * @param WishlistChangeInterface $changeTransfer
     *
     * @return WishlistInterface
     */
    public function ungroupRemovedItems(WishlistChangeInterface $changeTransfer)
    {
        return $this->getDependencyContainer()
            ->createTransferObjectIntegrator()
            ->ungroupRemovedItems($changeTransfer);
    }


    /**
     * @param WishlistChangeInterface $changeTransfer
     */
    public function storeItems(WishlistChangeInterface $changeTransfer)
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
    public function removeItem(WishlistChangeInterface $changeTransfer)
    {
        return  $this->getDependencyContainer()
            ->createEntityIntegrator()
            ->removeItems($changeTransfer);
    }

    /**
     * @param WishlistInterface $wishlist
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function mergeWishlist(WishlistInterface $wishlist)
    {
        return $this->getDependencyContainer()
            ->createMergeransferObjectIntegrator()
            ->mergeWishlist($wishlist);
    }


}
