<?php

namespace SprykerFeature\Client\Wishlist\Service;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use Generated\Shared\Wishlist\WishlistItemInterface;
use SprykerEngine\Client\Kernel\Service\AbstractClient;
use Generated\Shared\Transfer\WishlistTransfer;

class WishlistClient extends AbstractClient
{


    public function saveItem(WishlistItemInterface $wishlistItemTransfer)
    {
        $changeTransfer = (new WishlistChangeTransfer())->addItem($wishlistItemTransfer);
        $this->invokeCustomer($changeTransfer);
        $this->getDependencyContainer()
             ->createZedStub()
             ->saveItems($changeTransfer);
    }

    public function getWishlist()
    {
        $customer = $this->getCustomer();
        return $this->getDependencyContainer()
            ->createZedStub()
            ->findWishlistByCustomer($customer);
    }


    protected function invokeCustomer(WishlistChangeInterface $changeTransfer)
    {
        $customer = $this->getCustomer();

        if(!$customer instanceof CustomerInterface) {
            return;
        }

        $changeTransfer->setCustomer($customer);
    }

    protected function getCustomer()
    {
        return $this->getDependencyContainer()
            ->createSession()
            ->getCustomer();

    }


}
