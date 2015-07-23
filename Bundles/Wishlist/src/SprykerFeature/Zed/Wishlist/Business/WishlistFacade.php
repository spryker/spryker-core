<?php

namespace SprykerFeature\Zed\Wishlist\Business;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

class WishlistFacade extends AbstractFacade
{
    public function saveItems(WishlistChangeInterface $changeTransfer)
    {
        return $this->getDependencyContainer()
                    ->getEntityManager()
                    ->saveItems($changeTransfer);
    }

    public function getWishlist(CustomerInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
                    ->getTransferObjectManager()
                    ->getWishlistTransfer($customerTransfer);
    }

    public function getWishlistItemQuery()
    {
        return $this->getDependencyContainer()->getWishlistItemQuery();

    }


}
