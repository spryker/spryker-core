<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\WishlistsRestApi\Zed;

use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistItemResponseTransfer;
use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;
use Spryker\Client\WishlistsRestApi\Dependency\Client\WishlistsRestApiToZedRequestClientInterface;

class WishlistsRestApiStub implements WishlistsRestApiStubInterface
{
    /**
     * @var \Spryker\Client\WishlistsRestApi\Dependency\Client\WishlistsRestApiToZedRequestClientInterface
     */
    protected $zedStubClient;

    /**
     * @param \Spryker\Client\WishlistsRestApi\Dependency\Client\WishlistsRestApiToZedRequestClientInterface $zedStubClient
     */
    public function __construct(WishlistsRestApiToZedRequestClientInterface $zedStubClient)
    {
        $this->zedStubClient = $zedStubClient;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistRequestTransfer $wishlistRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function updateWishlist(WishlistRequestTransfer $wishlistRequestTransfer): WishlistResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\WishlistResponseTransfer $wishlistResponseTransfer */
        $wishlistResponseTransfer = $this->zedStubClient->call(
            '/wishlists-rest-api/gateway/update-wishlist',
            $wishlistRequestTransfer
        );

        return $wishlistResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistRequestTransfer $wishlistRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function deleteWishlist(WishlistRequestTransfer $wishlistRequestTransfer): WishlistResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\WishlistResponseTransfer $wishlistResponseTransfer */
        $wishlistResponseTransfer = $this->zedStubClient->call(
            '/wishlists-rest-api/gateway/delete-wishlist',
            $wishlistRequestTransfer
        );

        return $wishlistResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function addWishlistItem(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\WishlistItemResponseTransfer $wishlistItemResponseTransfer */
        $wishlistItemResponseTransfer = $this->zedStubClient->call(
            '/wishlists-rest-api/gateway/add-wishlist-item',
            $wishlistItemRequestTransfer
        );

        return $wishlistItemResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function deleteWishlistItem(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\WishlistItemResponseTransfer $wishlistItemResponseTransfer */
        $wishlistItemResponseTransfer = $this->zedStubClient->call(
            '/wishlists-rest-api/gateway/delete-wishlist-item',
            $wishlistItemRequestTransfer
        );

        return $wishlistItemResponseTransfer;
    }
}
