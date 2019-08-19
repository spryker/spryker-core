<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Business\Wishlist;

use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\WishlistsRestApi\Dependency\Facade\WishlistsRestApiToWishlistFacadeInterface;

class WishlistUpdater implements WishlistUpdaterInterface
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
        $wishlistResponseTransfer = $this->wishlistFacade->getWishlistByIdCustomerAndUuid($wishlistRequestTransfer);

        if (!$wishlistResponseTransfer->getIsSuccess()) {
            return $wishlistResponseTransfer;
        }

        $wishlistTransfer = $this->mergeWishlistTransfers(
            $wishlistResponseTransfer->getWishlist(),
            $wishlistRequestTransfer->getWishlist()
        );

        return $this->wishlistFacade->validateAndUpdateWishlist($wishlistTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $originalWishlistTransfer
     * @param \Generated\Shared\Transfer\WishlistTransfer $updatedWishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    protected function mergeWishlistTransfers(
        WishlistTransfer $originalWishlistTransfer,
        WishlistTransfer $updatedWishlistTransfer
    ): WishlistTransfer {
        return $originalWishlistTransfer->setName($updatedWishlistTransfer->getName());
    }
}
