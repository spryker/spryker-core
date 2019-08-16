<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\WishlistsRestApi\Zed;

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
    public function getWishlistByUuid(WishlistRequestTransfer $wishlistRequestTransfer): WishlistResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\WishlistResponseTransfer $wishlistResponseTransfer */
        $wishlistResponseTransfer = $this->zedStubClient->call(
            '/wishlists-rest-api/gateway/get-wishlist-by-uuid',
            $wishlistRequestTransfer
        );

        return $wishlistResponseTransfer;
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
}
