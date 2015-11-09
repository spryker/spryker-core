<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Wishlist\Service;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Wishlist\ItemInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use SprykerEngine\Client\Kernel\Service\AbstractClient;

/**
 * @method WishlistDependencyContainer getDependencyContainer()
 */
class WishlistClient extends AbstractClient implements WishlistClientInterface
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
    public function getWishlist()
    {
        $wishlistItems = $this->getSession()->getWishlist();
        $this->getStorage()->expandProductDetails($wishlistItems);

        return $wishlistItems;
    }

    /**
     * @return WishlistInterface
     */
    public function synchronizeSession()
    {
        $wishlistItems = $this->getSession()->getWishlist();

        $wishlistChange = new WishlistChangeTransfer();
        $customerTransfer = $this->getCustomerTransfer();
        $wishlistChange->setCustomer($customerTransfer);

        foreach ($wishlistItems->getItems() as $item) {
            $wishlistChange->addItem($item);
        }

        $wishlist = $this->getZedStub()->addItem($wishlistChange);
        $this->getSession()->setWishlist($wishlist);

        return $wishlist;
    }

    /**
     * @param ItemInterface $wishlistItemTransfer
     *
     * @return WishlistChangeTransfer
     */
    protected function createChangeTransfer(ItemInterface $wishlistItemTransfer)
    {
        $wishlistTransfer = $this->getSession()->getWishlist();

        $wishlistChange = new WishlistChangeTransfer();
        $wishlistChange->setWishlist($wishlistTransfer);
        $wishlistChange->addItem($wishlistItemTransfer);
        $customerTransfer = $this->getCustomerTransfer();

        if ($customerTransfer !== null) {
            $wishlistChange->setCustomer($customerTransfer);
        }

        return $wishlistChange;
    }

    /**
     * @return CustomerInterface
     */
    protected function getCustomerTransfer()
    {
        $customerClient = $this->getDependencyContainer()->createCustomerClient();
        $customerTransfer = $customerClient->getCustomer();

        return $customerTransfer;
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
