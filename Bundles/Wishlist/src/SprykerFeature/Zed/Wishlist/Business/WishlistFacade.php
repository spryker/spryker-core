<?php

namespace SprykerFeature\Zed\Wishlist\Business;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistItemInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method WishlistDependencyContainer getDependencyContainer()
 */
class WishlistFacade extends AbstractFacade
{
    public function saveItems(WishlistChangeInterface $changeTransfer)
    {
        $this->getDependencyContainer()
            ->getEntityIntegrator()
            ->saveItems($changeTransfer);
    }

    public function getWishlist(CustomerInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->getTransferObjectIntegrator()
            ->getWishlistTransfer($customerTransfer);
    }

    public function getWishlistItem(WishlistItemInterface $wishlistItemTransfer)
    {
        return $this->getDependencyContainer()
            ->getTransferObjectIntegrator()
            ->getWishlistItemTransfer($wishlistItemTransfer);
    }

    public function getWishlistItemQuery(WishlistItemInterface $wishlistItemTransfer)
    {
        return $this->getDependencyContainer()
            ->getTransferObjectIntegrator()
            ->getWishlistTransfer($wishlistItemTransfer);

    }

    public function removeItem(WishlistItemInterface $wishlistItemTransfer)
    {
        return  $this->getDependencyContainer()
            ->getEntityIntegrator()
            ->removeItem($wishlistItemTransfer);
    }


}
