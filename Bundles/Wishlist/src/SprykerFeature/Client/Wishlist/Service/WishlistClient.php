<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Wishlist\Service;


use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Wishlist\WishlistInterface;
use Generated\Shared\Wishlist\WishlistItemInterface;
use SprykerEngine\Client\Kernel\Service\AbstractClient;

/**
 * @method WishlistDependencyContainer getDependencyContainer()
 */
class WishlistClient extends AbstractClient
{
    public function addItem(WishlistItemInterface $wishlistItem)
    {
        $wishlist = $this->getSession()->getWishlist();

        $wishlistChange = new WishlistChangeTransfer();
        $wishlistChange->setWishlist($wishlist);
        $wishlistChange->addItem($wishlistItem);

        $wishlist = $this->getZedStub()->addItem($wishlistChange);

        $this->getSession()->setWishlist($wishlist);
    }

    /**
     * @return WishlistInterface
     */
    public function getWishlist()
    {
        $wishlistItems = $this->getSession()->getWishlist();
        return $this->getStorage()->expandProductDetails($wishlistItems);
    }

    public function increaseItemQuantity(WishlistItemInterface $wishlistItem)
    {
        $wishlist = $this->getSession()->getWishlist();

        $wishlistChange = new WishlistChangeTransfer();
        $wishlistChange->setWishlist($wishlist);
        $wishlistChange->addItem($wishlistItem);

        $wishlist = $this->getZedStub()->increaseQuantity($wishlistChange);

        $this->getSession()->setWishlist($wishlist);
    }

    public function decreaseItemQuantity(WishlistItemInterface $wishlistItem)
    {
        $wishlist = $this->getSession()->getWishlist();

        $wishlistChange = new WishlistChangeTransfer();
        $wishlistChange->setWishlist($wishlist);
        $wishlistChange->addItem($wishlistItem);

        $wishlist = $this->getZedStub()->descreaseQuantity($wishlistChange);

        $this->getSession()->setWishlist($wishlist);
    }


    public function remoteItem(WishlistItemInterface $wishlistItem)
    {
        $wishlist = $this->getSession()->getWishlist();

        $wishlistChange = new WishlistChangeTransfer();
        $wishlistChange->setWishlist($wishlist);
        $wishlistChange->addItem($wishlistItem);

        $wishlist = $this->getZedStub()->removeItem($wishlistChange);

        $this->getSession()->setWishlist($wishlist);
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
