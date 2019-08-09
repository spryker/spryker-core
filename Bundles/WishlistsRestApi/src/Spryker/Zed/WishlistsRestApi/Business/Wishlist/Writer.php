<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Business\Wishlist;

use Generated\Shared\Transfer\RestWishlistsAttributesTransfer;
use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\WishlistsRestApi\Dependency\Facade\WishlistsRestApiToWishlistFacadeInterface;

class Writer implements WriterInterface
{
    /**
     * @var \Spryker\Zed\WishlistsRestApi\Dependency\Facade\WishlistsRestApiToWishlistFacadeInterface
     */
    protected $wishlistFacade;

    /**
     * @param \Spryker\Zed\WishlistsRestApi\Dependency\Facade\WishlistsRestApiToWishlistFacadeInterface $wishlistFacade
     */
    public function __construct(WishlistsRestApiToWishlistFacadeInterface $wishlistFacade)
    {
        $this->wishlistFacade = $wishlistFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistRequestTransfer $wishlistRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function updateWishlist(WishlistRequestTransfer $wishlistRequestTransfer): WishlistResponseTransfer
    {
        $wishlistResponseTransfer = $this->wishlistFacade->getCustomerWishlistByUuid($wishlistRequestTransfer);

        //ToDo: Check if wishlist was found, and add error

        $wishlistTransfer = $this->mapWishlistAttributesToWishlistTransfer(
            $wishlistResponseTransfer->getWishlist(),
            $wishlistRequestTransfer->getRestWishlistsAttributes()
        );

        return $this->wishlistFacade->validateAndUpdateWishlist($wishlistTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistRequestTransfer $wishlistRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function deleteWishlist(WishlistRequestTransfer $wishlistRequestTransfer): WishlistResponseTransfer
    {
        $wishlistResponseTransfer = $this->wishlistFacade->getCustomerWishlistByUuid($wishlistRequestTransfer);

        if (!$wishlistResponseTransfer->getIsSuccess()) {
            return $wishlistResponseTransfer;
        }

        $this->wishlistFacade->removeWishlist($wishlistResponseTransfer->getWishlist());

        return $wishlistResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     * @param \Generated\Shared\Transfer\RestWishlistsAttributesTransfer $attributesTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    protected function mapWishlistAttributesToWishlistTransfer(
        WishlistTransfer $wishlistTransfer,
        RestWishlistsAttributesTransfer $attributesTransfer
    ): WishlistTransfer {
        return $wishlistTransfer->fromArray($attributesTransfer->modifiedToArray(), true);
    }
}
