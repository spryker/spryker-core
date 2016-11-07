<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist;

use Generated\Shared\Transfer\WishlistItemUpdateRequestTransfer;
use Generated\Shared\Transfer\WishlistMoveToCartRequestTransfer;
use Generated\Shared\Transfer\WishlistOverviewRequestTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Wishlist\WishlistFactory getFactory()
 */
class WishlistClient extends AbstractClient implements WishlistClientInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function createWishlist(WishlistTransfer $wishlistTransfer)
    {
        return $this->getZedStub()->createWishlist($wishlistTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function updateWishlist(WishlistTransfer $wishlistTransfer)
    {
        return $this->getZedStub()->updateWishlist($wishlistTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeWishlist(WishlistTransfer $wishlistTransfer)
    {
        return $this->getZedStub()->removeWishlist($wishlistTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemUpdateRequestTransfer $wishlistItemUpdateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemUpdateRequestTransfer
     */
    public function addItem(WishlistItemUpdateRequestTransfer $wishlistItemUpdateRequestTransfer)
    {
        return $this->getZedStub()->addItem($wishlistItemUpdateRequestTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemUpdateRequestTransfer $wishlistItemUpdateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemUpdateRequestTransfer
     */
    public function removeItem(WishlistItemUpdateRequestTransfer $wishlistItemUpdateRequestTransfer)
    {
        return $this->getZedStub()->removeItem($wishlistItemUpdateRequestTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestTransfer $wishlistMoveToCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistMoveToCartRequestTransfer
     */
    public function moveToCart(WishlistMoveToCartRequestTransfer $wishlistMoveToCartRequestTransfer)
    {
        return $this->createCartHandler()->moveToCart($wishlistMoveToCartRequestTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getWishlist(WishlistTransfer $wishlistTransfer)
    {
        return $this->getZedStub()->getWishlist($wishlistTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistOverviewRequestTransfer $wishlistOverviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistOverviewResponseTransfer
     */
    public function getWishlistOverview(WishlistOverviewRequestTransfer $wishlistOverviewRequestTransfer)
    {
        $wishlistOverviewResponse = $this->getZedStub()->getWishlistOverview($wishlistOverviewRequestTransfer);
        return $this->getStorage()->expandProductDetails($wishlistOverviewResponse);
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

    /**
     * @return \Spryker\Client\Product\ProductClientInterface
     */
    protected function getProductClient()
    {
        return $this->getFactory()->createProductClient();
    }

    /**
     * @return \Spryker\Client\Wishlist\Cart\CartHandlerInterface
     */
    protected function createCartHandler()
    {
        return $this->getFactory()->createCartHandler(
            $this->getFactory()->createCartClient(),
            $this
        );
    }

}
