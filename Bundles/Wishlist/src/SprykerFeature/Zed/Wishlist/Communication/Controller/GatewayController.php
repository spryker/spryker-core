<?php

namespace SprykerFeature\Zed\Wishlist\Communication\Controller;


use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistItemInterface;
use SprykerFeature\Zed\Kernel\Communication\Controller\AbstractGatewayController;

class GatewayController extends AbstractGatewayController
{

    public function getWishlistItemAction(WishlistItemInterface $wishlistItemTransfer)
    {
        return $this->getFacade()->getWishlistItem($wishlistItemTransfer);
    }

    public function getWishlistAction(CustomerInterface $customerTransfer)
    {
        return $this->getFacade()->getWishlist($customerTransfer);
    }

    public function saveAction(WishlistChangeInterface $changeTransfer)
    {
        return $this->getFacade()->saveItems($changeTransfer);
    }

    public function removeAction(WishlistItemInterface $itemTransfer)
    {
        return $this->getFacade()->removeItem($itemTransfer);
    }

}
