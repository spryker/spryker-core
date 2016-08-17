<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Wishlist\WishlistFactory getFactory()
 */
class WishlistClient extends AbstractClient implements WishlistClientInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $wishlistItem
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function addItem(ItemTransfer $wishlistItem)
    {
        $wishlistChange = $this->createChangeTransfer($wishlistItem);
        $wishlist = $this->getZedStub()->addItem($wishlistChange);
        $this->getSession()->setWishlist($wishlist);

        return $wishlist;
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $wishlistItem
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function increaseItemQuantity(ItemTransfer $wishlistItem)
    {
        $wishlistChange = $this->createChangeTransfer($wishlistItem);
        $wishlist = $this->getZedStub()->increaseQuantity($wishlistChange);

        $this->getSession()->setWishlist($wishlist);

        return $wishlist;
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $wishlistItem
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function decreaseItemQuantity(ItemTransfer $wishlistItem)
    {
        $wishlistChange = $this->createChangeTransfer($wishlistItem);
        $wishlist = $this->getZedStub()->descreaseQuantity($wishlistChange);

        $this->getSession()->setWishlist($wishlist);

        return $wishlist;
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $wishlistItem
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeItem(ItemTransfer $wishlistItem)
    {
        $wishlistChange = $this->createChangeTransfer($wishlistItem);
        $wishlist = $this->getZedStub()->removeItem($wishlistChange);
        $this->getSession()->setWishlist($wishlist);

        return $wishlist;
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getWishlist()
    {
        $wishlistItems = $this->getSession()->getWishlist();
        $this->getStorage()->expandProductDetails($wishlistItems);

        return $wishlistItems;
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
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
     * @param \Generated\Shared\Transfer\ItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistChangeTransfer
     */
    protected function createChangeTransfer(ItemTransfer $wishlistItemTransfer)
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
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomerTransfer()
    {
        $customerClient = $this->getFactory()->createCustomerClient();
        $customerTransfer = $customerClient->getCustomer();

        return $customerTransfer;
    }

    /**
     * @return \Spryker\Client\Wishlist\Session\WishlistSessionInterface
     */
    protected function getSession()
    {
        return $this->getFactory()->createSession();
    }

    /**
     * @return \Spryker\Client\Wishlist\Zed\WishlistStubInterface
     */
    protected function getZedStub()
    {
        return $this->getFactory()->createZedStub();
    }

    /**
     * @return \Spryker\Client\Wishlist\Storage\WishlistStorageInterface
     */
    protected function getStorage()
    {
        return $this->getFactory()->createStorage();
    }

}
