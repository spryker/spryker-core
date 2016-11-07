<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business;

use Generated\Shared\Transfer\WishlistItemUpdateRequestTransfer;
use Generated\Shared\Transfer\WishlistOverviewRequestTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Wishlist\Business\WishlistBusinessFactory getFactory()
 */
class WishlistFacade extends AbstractFacade implements WishlistFacadeInterface
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
        return $this->getFactory()
            ->createWriter()
            ->createWishlist($wishlistTransfer);
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
        return $this->getFactory()
            ->createWriter()
            ->updateWishlist($wishlistTransfer);
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
        return $this->getFactory()
            ->createWriter()
            ->removeWishlist($wishlistTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     * @param array|\Generated\Shared\Transfer\WishlistItemTransfer[] $wishlistItemCollection
     *
     * @return void
     */
    public function addItemCollection(WishlistTransfer $wishlistTransfer, array $wishlistItemCollection)
    {
        $this->getFactory()
            ->createWriter()
            ->addItemCollection($wishlistTransfer, $wishlistItemCollection);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return void
     */
    public function removeItemCollection(WishlistTransfer $wishlistTransfer)
    {
        $this->getFactory()
            ->createWriter()
            ->emptyWishlist($wishlistTransfer);
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
        return $this->getFactory()
            ->createWriter()
            ->addItem($wishlistItemUpdateRequestTransfer);
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
        return $this->getFactory()
            ->createWriter()
            ->removeItem($wishlistItemUpdateRequestTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getWishlistByName(WishlistTransfer $wishlistTransfer)
    {
        return $this->getFactory()
            ->createReader()
            ->getWishlistByName($wishlistTransfer);
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
        return $this->getFactory()
            ->createReader()
            ->getWishlistOverview($wishlistOverviewRequestTransfer);
    }

}
