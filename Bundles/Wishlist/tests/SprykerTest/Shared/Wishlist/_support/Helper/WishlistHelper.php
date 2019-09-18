<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Wishlist\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\WishlistBuilder;
use Generated\Shared\DataBuilder\WishlistItemBuilder;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\Wishlist\Business\WishlistFacadeInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class WishlistHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function haveEmptyWishlist(array $override): WishlistTransfer
    {
        $wishlistTransfer = (new WishlistBuilder($override))
            ->build();

        $createdWishlistTransfer = $this->getWishlistFacade()->createWishlist($wishlistTransfer);

        return $createdWishlistTransfer;
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function haveItemInWishlist(array $override): WishlistItemTransfer
    {
        $wishlistItemTransfer = (new WishlistItemBuilder($override))
            ->build();

        $createdWishlistItemTransfer = $this->getWishlistFacade()->addItem($wishlistItemTransfer);

        return $createdWishlistItemTransfer;
    }

    /**
     * @return \Spryker\Zed\Wishlist\Business\WishlistFacadeInterface
     */
    public function getWishlistFacade(): WishlistFacadeInterface
    {
        return $this->getLocatorHelper()->getLocator()->wishlist()->facade();
    }
}
