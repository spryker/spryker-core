<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist;

use Generated\Shared\Transfer\WishlistItemTransfer;
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
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function addItem(WishlistItemTransfer $wishlistItemTransfer)
    {
        return $this->getZedStub()->addItem($wishlistItemTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeItem(WishlistItemTransfer $wishlistItemTransfer)
    {
        return $this->getZedStub()->removeItem($wishlistItemTransfer);
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
     * @param \Generated\Shared\Transfer\WishlistOverviewRequestTransfer $wishlistOverviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistOverviewResponseTransfer
     */
    public function getWishlistOverview(WishlistOverviewRequestTransfer $wishlistOverviewRequestTransfer)
    {
        return $this->getZedStub()->getWishlistOverview($wishlistOverviewRequestTransfer);
    }

    /**
     * @return \Spryker\Client\Wishlist\Zed\WishlistStubInterface
     */
    protected function getZedStub()
    {
        return $this->getFactory()->createZedStub();
    }

}
