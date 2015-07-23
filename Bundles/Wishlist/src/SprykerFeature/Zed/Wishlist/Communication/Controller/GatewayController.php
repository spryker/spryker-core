<?php

namespace SprykerFeature\Zed\Wishlist\Communication\Controller;


use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use SprykerFeature\Zed\Kernel\Communication\Controller\AbstractGatewayController;

class GatewayController extends AbstractGatewayController
{
    public function indexAction()
    {

    }

    public function getWishlistAction(CustomerInterface $customerTransfer)
    {
        return $this->getFacade()->getWishlist($customerTransfer);
    }

    public function saveAction(WishlistChangeInterface $changeTransfer)
    {
        return $this->getFacade()->saveItems($changeTransfer);
    }

}
