<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\WishlistsRestApi\Zed;

use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;
use Spryker\Client\ZedRequest\ZedRequestClient;

class WishlistsRestApiStub implements WishlistsRestApiStubInterface
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
     * @param \Generated\Shared\Transfer\WishlistRequestTransfer $wishlistRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function getCustomerWishlistByUuid(WishlistRequestTransfer $wishlistRequestTransfer): WishlistResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\WishlistResponseTransfer $wishlistResponseTransfer */
        $wishlistResponseTransfer = $this->zedStub->call(
            '/wishlists-rest-api/gateway/get-customer-wishlist-by-uuid',
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
        $wishlistResponseTransfer = $this->zedStub->call(
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
        $wishlistResponseTransfer = $this->zedStub->call(
            '/wishlists-rest-api/gateway/delete-wishlist',
            $wishlistRequestTransfer
        );

        return $wishlistResponseTransfer;
    }
}
