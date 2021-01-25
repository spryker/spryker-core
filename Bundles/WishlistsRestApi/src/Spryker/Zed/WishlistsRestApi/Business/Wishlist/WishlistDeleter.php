<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Business\Wishlist;

use Generated\Shared\Transfer\WishlistFilterTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;
use Spryker\Shared\WishlistsRestApi\WishlistsRestApiConfig;
use Spryker\Zed\WishlistsRestApi\Dependency\Facade\WishlistsRestApiToWishlistFacadeInterface;

class WishlistDeleter implements WishlistDeleterInterface
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
     * @param \Generated\Shared\Transfer\WishlistFilterTransfer $wishlistFilterTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function deleteWishlist(WishlistFilterTransfer $wishlistFilterTransfer): WishlistResponseTransfer
    {
        $wishlistResponseTransfer = $this->wishlistFacade->getWishlistByFilter($wishlistFilterTransfer);

        if (!$wishlistResponseTransfer->getIsSuccess()) {
            $wishlistResponseTransfer->setErrorIdentifier(WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NOT_FOUND);

            return $wishlistResponseTransfer;
        }

        $this->wishlistFacade->removeWishlist($wishlistResponseTransfer->getWishlist());

        return $wishlistResponseTransfer;
    }
}
