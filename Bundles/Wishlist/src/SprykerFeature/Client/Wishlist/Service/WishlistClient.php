<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Wishlist\Service;


use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Wishlist\ItemInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use SprykerEngine\Client\Kernel\Service\AbstractClient;

/**
 * @method WishlistDependencyContainer getDependencyContainer()
 */
class WishlistClient extends AbstractClient
{
    /**
     * @param ItemInterface     $wishlistItem
     * @param CustomerInterface $customerTransfer
     *
     * @return WishlistInterface
     */
    public function addItem(ItemInterface $wishlistItem, CustomerInterface $customerTransfer = null)
    {
        $wishlistChange = $this->createChangeTransfer($wishlistItem, $customerTransfer);
        $wishlist = $this->getZedStub()->addItem($wishlistChange);
        $this->getSession()->setWishlist($wishlist);

        return $wishlist;
    }

    /**
     * @param ItemInterface     $wishlistItem
     * @param CustomerInterface $customerTransfer
     *
     * @return WishlistInterface
     */
    public function increaseItemQuantity(ItemInterface $wishlistItem, CustomerInterface $customerTransfer = null)
    {
        $wishlistChange = $this->createChangeTransfer($wishlistItem, $customerTransfer);
        $wishlist = $this->getZedStub()->increaseQuantity($wishlistChange);

        $this->getSession()->setWishlist($wishlist);

        return $wishlist;
    }

    /**
     * @param ItemInterface     $wishlistItem
     * @param CustomerInterface $customerTransfer
     *
     * @return WishlistInterface
     */
    public function decreaseItemQuantity(ItemInterface $wishlistItem, CustomerInterface $customerTransfer = null)
    {
        $wishlistChange = $this->createChangeTransfer($wishlistItem, $customerTransfer);
        $wishlist = $this->getZedStub()->descreaseQuantity($wishlistChange);

        $this->getSession()->setWishlist($wishlist);

        return $wishlist;
    }

    /**
     * @param ItemInterface     $wishlistItem
     * @param CustomerInterface $customerTransfer
     *
     * @return WishlistInterface
     */
    public function removeItem(ItemInterface $wishlistItem, CustomerInterface $customerTransfer = null)
    {
        $wishlistChange = $this->createChangeTransfer($wishlistItem, $customerTransfer);
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
        $this->getStorage()->expandProductDetails($wishlistItems);
        return $wishlistItems;
    }

    /**
     * @param CustomerInterface $customer
     */
    public function synchronizeSession(CustomerInterface $customer)
    {
        $wishlistItems = $this->getSession()->getWishlist();

        $wishlistChange = new WishlistChangeTransfer();
        $wishlistChange->setCustomer($customer);

        foreach($wishlistItems->getItems() as $item) {
            $wishlistChange->addItem($item);
        }

        $wishlist = $this->getZedStub()->addItem($wishlistChange);
        $this->getSession()->setWishlist($wishlist);

    }

    /**
     * @param ItemInterface     $wishlistItem
     *
     * @param CustomerInterface $customerTransfer
     *
     * @return WishlistChangeTransfer
     */
    protected function createChangeTransfer(ItemInterface $wishlistItem, CustomerInterface $customerTransfer = null)
    {
        $wishlist = $this->getSession()->getWishlist();

        $wishlistChange = new WishlistChangeTransfer();
        $wishlistChange->setWishlist($wishlist);
        $wishlistChange->addItem($wishlistItem);
        if (null !== $customerTransfer) {
            $wishlistChange->setCustomer($customerTransfer);
        }

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
