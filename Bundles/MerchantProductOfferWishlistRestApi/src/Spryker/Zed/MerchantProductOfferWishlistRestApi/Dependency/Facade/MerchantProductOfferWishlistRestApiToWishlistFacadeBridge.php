<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferWishlistRestApi\Dependency\Facade;

use Generated\Shared\Transfer\WishlistItemTransfer;

class MerchantProductOfferWishlistRestApiToWishlistFacadeBridge implements MerchantProductOfferWishlistRestApiToWishlistFacadeInterface
{
    /**
     * @var \Spryker\Zed\Wishlist\Business\WishlistFacadeInterface
     */
    protected $wishlistFacade;

    /**
     * @param \Spryker\Zed\Wishlist\Business\WishlistFacadeInterface $wishlistFacade
     */
    public function __construct($wishlistFacade)
    {
        $this->wishlistFacade = $wishlistFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function removeItem(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer
    {
        return $this->wishlistFacade->removeItem($wishlistItemTransfer);
    }
}
