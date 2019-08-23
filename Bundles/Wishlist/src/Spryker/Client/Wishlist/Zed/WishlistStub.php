<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist\Zed;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistItemCollectionTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistOverviewRequestTransfer;
use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Client\ZedRequest\ZedRequestClient;

class WishlistStub implements WishlistStubInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClient
     */
    protected $zedStub;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function createWishlist(WishlistTransfer $wishlistTransfer)
    {
        /** @var \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer */
        $wishlistTransfer = $this->zedStub->call('/wishlist/gateway/create-wishlist', $wishlistTransfer);

        return $wishlistTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function validateAndCreateWishlist(WishlistTransfer $wishlistTransfer)
    {
        /** @var \Generated\Shared\Transfer\WishlistResponseTransfer $wishlistResponseTransfer */
        $wishlistResponseTransfer = $this->zedStub->call('/wishlist/gateway/validate-and-create-wishlist', $wishlistTransfer);

        return $wishlistResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function updateWishlist(WishlistTransfer $wishlistTransfer)
    {
        /** @var \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer */
        $wishlistTransfer = $this->zedStub->call('/wishlist/gateway/update-wishlist', $wishlistTransfer);

        return $wishlistTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function validateAndUpdateWishlist(WishlistTransfer $wishlistTransfer)
    {
        /** @var \Generated\Shared\Transfer\WishlistResponseTransfer $wishlistResponseTransfer */
        $wishlistResponseTransfer = $this->zedStub->call('/wishlist/gateway/validate-and-update-wishlist', $wishlistTransfer);

        return $wishlistResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeWishlist(WishlistTransfer $wishlistTransfer)
    {
        /** @var \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer */
        $wishlistTransfer = $this->zedStub->call('/wishlist/gateway/remove-wishlist', $wishlistTransfer);

        return $wishlistTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function removeWishlistByName(WishlistTransfer $wishlistTransfer)
    {
        /** @var \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer */
        $wishlistTransfer = $this->zedStub->call('/wishlist/gateway/remove-wishlist-by-name', $wishlistTransfer);

        return $wishlistTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function addItem(WishlistItemTransfer $wishlistItemTransfer)
    {
        /** @var \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer */
        $wishlistItemTransfer = $this->zedStub->call('/wishlist/gateway/add-item', $wishlistItemTransfer);

        return $wishlistItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function removeItem(WishlistItemTransfer $wishlistItemTransfer)
    {
        /** @var \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer */
        $wishlistItemTransfer = $this->zedStub->call('/wishlist/gateway/remove-item', $wishlistItemTransfer);

        return $wishlistItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemCollectionTransfer $wishlistItemTransferCollection
     *
     * @return \Generated\Shared\Transfer\WishlistItemCollectionTransfer
     */
    public function removeItemCollection(WishlistItemCollectionTransfer $wishlistItemTransferCollection)
    {
        /** @var \Generated\Shared\Transfer\WishlistItemCollectionTransfer $wishlistItemCollectionTransfer */
        $wishlistItemCollectionTransfer = $this->zedStub->call('/wishlist/gateway/remove-item-collection', $wishlistItemTransferCollection);

        return $wishlistItemCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getWishlist(WishlistTransfer $wishlistTransfer)
    {
        /** @var \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer */
        $wishlistTransfer = $this->zedStub->call('/wishlist/gateway/get-wishlist', $wishlistTransfer);

        return $wishlistTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistOverviewRequestTransfer $wishlistOverviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistOverviewResponseTransfer
     */
    public function getWishlistOverview(WishlistOverviewRequestTransfer $wishlistOverviewRequestTransfer)
    {
        /** @var \Generated\Shared\Transfer\WishlistOverviewResponseTransfer $wishlistOverviewResponseTransfer */
        $wishlistOverviewResponseTransfer = $this->zedStub->call('/wishlist/gateway/get-wishlist-overview', $wishlistOverviewRequestTransfer);

        return $wishlistOverviewResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistCollectionTransfer
     */
    public function getCustomerWishlistCollection(CustomerTransfer $customerTransfer)
    {
        /** @var \Generated\Shared\Transfer\WishlistCollectionTransfer $wishlistCollectionTransfer */
        $wishlistCollectionTransfer = $this->zedStub->call('/wishlist/gateway/get-customer-wishlist-collection', $customerTransfer);

        return $wishlistCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistRequestTransfer $wishlistRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function getWishlistByIdCustomerAndUuid(WishlistRequestTransfer $wishlistRequestTransfer): WishlistResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\WishlistResponseTransfer $wishlistResponseTransfer */
        $wishlistResponseTransfer = $this->zedStub->call('/wishlist/gateway/get-wishlist-by-id-customer-and-uuid', $wishlistRequestTransfer);

        return $wishlistResponseTransfer;
    }
}
