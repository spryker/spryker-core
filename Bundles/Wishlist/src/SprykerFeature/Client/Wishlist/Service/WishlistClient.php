<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Wishlist\Service;


use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Wishlist\ItemInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use Generated\Shared\Wishlist\WishlistItemInterface;
use SprykerEngine\Client\Kernel\Service\AbstractClient;

/**
 * @method WishlistDependencyContainer getDependencyContainer()
 */
class WishlistClient extends AbstractClient
{
    /**
     * @param ItemInterface $wishlistItem
     *
     * @return WishlistInterface
     */
    public function addItem(ItemInterface $wishlistItem)
    {
        $wishlistChange = $this->createChangeTransfer($wishlistItem);
        $wishlist = $this->getZedStub()->addItem($wishlistChange);
        $this->getSession()->setWishlist($wishlist);

        return $wishlist;
    }

    /**
     * @param ItemInterface $wishlistItem
     *
     * @return WishlistInterface
     */
    public function increaseItemQuantity(ItemInterface $wishlistItem)
    {
        $wishlistChange = $this->createChangeTransfer($wishlistItem);
        $wishlist = $this->getZedStub()->increaseQuantity($wishlistChange);

        $this->getSession()->setWishlist($wishlist);

        return $wishlist;
    }

    /**
     * @param ItemInterface $wishlistItem
     *
     * @return WishlistInterface
     */
    public function decreaseItemQuantity(ItemInterface $wishlistItem)
    {
        $wishlistChange = $this->createChangeTransfer($wishlistItem);
        $wishlist = $this->getZedStub()->descreaseQuantity($wishlistChange);

        $this->getSession()->setWishlist($wishlist);

        return $wishlist;
    }

    /**
     * @param ItemInterface $wishlistItem
     *
     * @return WishlistInterface
     */
    public function removeItem(ItemInterface $wishlistItem)
    {
        $wishlistChange = $this->createChangeTransfer($wishlistItem);
        $wishlist = $this->getZedStub()->removeItem($wishlistChange);

        $this->getSession()->setWishlist($wishlist);

        return $wishlist;
    }

    /**
     * @return WishlistInterface
     */
    public function getCustomerWishlist()
    {
        $wishlistItems = $this->getSession()->getWishlist();
        return $this->getStorage()->expandProductDetails($wishlistItems);
    }

    /**
     * @param ItemInterface $wishlistItem
     *
     * @return WishlistChangeTransfer
     */
    protected function createChangeTransfer(ItemInterface $wishlistItem)
    {
        $wishlist = $this->getSession()->getWishlist();

        $customer = new CustomerTransfer();
        $customer->setIdCustomer(1);

        $wishlistChange = new WishlistChangeTransfer();
        $wishlistChange->setWishlist($wishlist);
        $wishlistChange->addItem($wishlistItem);
        $wishlistChange->setCustomer($customer);

        return $wishlistChange;
    }

    /**
     * @return Session\WishlistSessionInterface
     */
    protected function getSession()
    {
        return $this->getDependencyContainer()->createSession();
    }

    /**
     * @return Zed\WishlistStubInterface
     */
    protected function getZedStub()
    {
        return $this->getDependencyContainer()->createZedStub();
    }

    /**
     * @return Storage\WishlistStorageInterface
     */
    protected function getStorage()
    {
        return $this->getDependencyContainer()->createStorage();
    }
}
