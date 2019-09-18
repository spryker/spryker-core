<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistItemCollectionTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistOverviewRequestTransfer;
use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Wishlist\Business\WishlistFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function createWishlistAction(WishlistTransfer $wishlistTransfer)
    {
        return $this->getFacade()->createWishlist($wishlistTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function validateAndCreateWishlistAction(WishlistTransfer $wishlistTransfer)
    {
        return $this->getFacade()->validateAndCreateWishlist($wishlistTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function updateWishlistAction(WishlistTransfer $wishlistTransfer)
    {
        return $this->getFacade()->updateWishlist($wishlistTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function validateAndUpdateWishlistAction(WishlistTransfer $wishlistTransfer)
    {
        return $this->getFacade()->validateAndUpdateWishlist($wishlistTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeWishlistAction(WishlistTransfer $wishlistTransfer)
    {
        return $this->getFacade()->removeWishlist($wishlistTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeWishlistByNameAction(WishlistTransfer $wishlistTransfer)
    {
        return $this->getFacade()->removeWishlistByName($wishlistTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemUpdateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function addItemAction(WishlistItemTransfer $wishlistItemUpdateRequestTransfer)
    {
        return $this->getFacade()->addItem($wishlistItemUpdateRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemUpdateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function removeItemAction(WishlistItemTransfer $wishlistItemUpdateRequestTransfer)
    {
        return $this->getFacade()->removeItem($wishlistItemUpdateRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemCollectionTransfer $wishlistItemTransferCollection
     *
     * @return \Generated\Shared\Transfer\WishlistItemCollectionTransfer
     */
    public function removeItemCollectionAction(WishlistItemCollectionTransfer $wishlistItemTransferCollection)
    {
        return $this->getFacade()->removeItemCollection($wishlistItemTransferCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getWishlistAction(WishlistTransfer $wishlistTransfer)
    {
        return $this->getFacade()->getWishlistByName($wishlistTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistOverviewRequestTransfer $wishlistOverviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistOverviewResponseTransfer
     */
    public function getWishlistOverviewAction(WishlistOverviewRequestTransfer $wishlistOverviewRequestTransfer)
    {
        return $this->getFacade()->getWishlistOverview($wishlistOverviewRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistCollectionTransfer
     */
    public function getCustomerWishlistCollectionAction(CustomerTransfer $customerTransfer)
    {
        return $this->getFacade()->getCustomerWishlistCollection($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistRequestTransfer $wishlistRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function getCustomerWishlistByUuidAction(WishlistRequestTransfer $wishlistRequestTransfer)
    {
        return $this->getFacade()->getCustomerWishlistByUuid($wishlistRequestTransfer);
    }
}
